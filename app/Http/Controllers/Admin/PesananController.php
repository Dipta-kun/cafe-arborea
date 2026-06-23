<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Pesanan::with(['meja'])->latest();

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->tanggal) {
                $query->whereDate('created_at', $request->tanggal);
            }

            $pesanan = $query->get()->map(function ($p) {
                return [
                    'id'                    => $p->id,
                    'kode_pesanan'          => $p->kode_pesanan,
                    'nama_pelanggan'        => $p->nama_pelanggan,
                    'nomor_meja'            => $p->meja?->nomor_meja,
                    'total_harga_format'    => $p->total_harga_format,
                    'total_item'            => $p->total_item,
                    'status'                => $p->status,
                    'status_label'          => $p->status_label,
                    'status_color'          => $p->status_color,
                    'metode_pembayaran'     => $p->metode_pembayaran,
                    'payment_method_label'  => $p->payment_method_label,
                    'status_pembayaran'     => $p->status_pembayaran,
                    'payment_status_label'  => $p->payment_status_label,
                    'payment_status_color'  => $p->payment_status_color,
                    'waktu_pesan'           => $p->waktu_pesan?->format('d/m/Y H:i'),
                    'created_at'            => $p->created_at->format('d/m/Y H:i'),
                ];
            });

            return response()->json(['data' => $pesanan]);
        }

        $statusList = [
            'menunggu'      => 'Menunggu',
            'diproses'      => 'Diproses',
            'siap_disajikan'=> 'Siap Disajikan',
            'selesai'       => 'Selesai',
            'dibatalkan'    => 'Dibatalkan',
        ];

        return view('admin.pesanan.index', compact('statusList'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['meja', 'detailPesanan']);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => ['required', 'in:menunggu,diproses,siap_disajikan,selesai,dibatalkan'],
        ]);

        $updates = ['status' => $request->status];

        if ($request->status === 'selesai') {
            $updates['waktu_selesai'] = now();
        }

        $pesanan->update($updates);

        return response()->json([
            'success'      => true,
            'message'      => 'Status pesanan diperbarui.',
            'status'       => $pesanan->status,
            'status_label' => $pesanan->status_label,
            'status_color' => $pesanan->status_color,
        ]);
    }

    public function konfirmasiPembayaran(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->status_pembayaran === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah lunas.',
            ], 422);
        }

        $pesanan->update([
            'status_pembayaran' => 'lunas',
            'status'            => $pesanan->status === 'menunggu' ? 'diproses' : $pesanan->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran dikonfirmasi sebagai LUNAS.',
        ]);
    }
}
