<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function getData(Request $request)
    {
        $request->validate([
            'filter' => ['required', 'in:harian,bulanan,tahunan'],
            'tahun'  => ['nullable', 'integer'],
            'bulan'  => ['nullable', 'integer', 'between:1,12'],
            'tanggal'=> ['nullable', 'date'],
        ]);

        $query = Pesanan::with('detailPesanan')
            ->whereIn('status', ['selesai', 'siap_disajikan']);

        switch ($request->filter) {
            case 'harian':
                $tanggal = $request->tanggal ?? today()->toDateString();
                $query->whereDate('created_at', $tanggal);
                break;
            case 'bulanan':
                $query->whereYear('created_at', $request->tahun ?? date('Y'))
                      ->whereMonth('created_at', $request->bulan ?? date('m'));
                break;
            case 'tahunan':
                $query->whereYear('created_at', $request->tahun ?? date('Y'));
                break;
        }

        $pesanan     = $query->get();
        $totalPendapatan = $pesanan->sum('total_harga');
        $totalPesanan    = $pesanan->count();
        $totalItem       = $pesanan->sum('total_item');

        return response()->json([
            'data'              => $pesanan,
            'total_pendapatan'  => $totalPendapatan,
            'total_pendapatan_format' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
            'total_pesanan'     => $totalPesanan,
            'total_item'        => $totalItem,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'filter' => ['required', 'in:harian,bulanan,tahunan'],
            'tahun'  => ['nullable', 'integer'],
            'bulan'  => ['nullable', 'integer'],
            'tanggal'=> ['nullable', 'date'],
        ]);

        $query = Pesanan::with(['meja', 'detailPesanan'])
            ->whereIn('status', ['selesai', 'siap_disajikan'])
            ->orderBy('created_at');

        $judulLaporan = 'Laporan Penjualan';

        switch ($request->filter) {
            case 'harian':
                $tanggal = $request->tanggal ?? today()->toDateString();
                $query->whereDate('created_at', $tanggal);
                $judulLaporan .= ' Harian – ' . \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y');
                break;
            case 'bulanan':
                $bulan = $request->bulan ?? date('m');
                $tahun = $request->tahun ?? date('Y');
                $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);
                $judulLaporan .= ' Bulanan – ' . \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->isoFormat('MMMM Y');
                break;
            case 'tahunan':
                $tahun = $request->tahun ?? date('Y');
                $query->whereYear('created_at', $tahun);
                $judulLaporan .= ' Tahunan – ' . $tahun;
                break;
        }

        $pesanan         = $query->get();
        $totalPendapatan = $pesanan->sum('total_harga');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('pesanan', 'totalPendapatan', 'judulLaporan'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('laporan_' . $request->filter . '_' . date('YmdHis') . '.pdf');
    }
}
