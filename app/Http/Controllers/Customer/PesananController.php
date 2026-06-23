<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\PesananRequest;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function proses(Request $request)
    {
        $request->validate([
            'nama_pelanggan'    => ['required', 'string', 'max:100'],
            'meja_id'           => ['required', 'exists:meja,id'],
            'metode_pembayaran' => ['required', 'in:qris,kasir'],
        ]);

        $cart = session('keranjang', []);

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 422);
        }

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $totalItem  = 0;
            $details    = [];

            foreach ($cart as $item) {
                $menu = Menu::findOrFail($item['menu_id']);

                if (!$menu->is_tersedia || $menu->stok <= 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => $menu->nama_menu . ' sudah tidak tersedia.'
                    ], 422);
                }

                $subtotal    = $menu->harga * $item['jumlah'];
                $totalHarga += $subtotal;
                $totalItem  += $item['jumlah'];

                $details[] = [
                    'menu_id'             => $menu->id,
                    'nama_menu_snapshot'  => $menu->nama_menu,
                    'harga_snapshot'      => $menu->harga,
                    'jumlah'              => $item['jumlah'],
                    'subtotal'            => $subtotal,
                ];

                // Kurangi stok
                $menu->decrement('stok', $item['jumlah']);
            }

            $pesanan = Pesanan::create([
                'kode_pesanan'      => Pesanan::generateKode(),
                'meja_id'           => $request->meja_id,
                'nama_pelanggan'    => $request->nama_pelanggan,
                'total_harga'       => $totalHarga,
                'total_item'        => $totalItem,
                'status'            => 'menunggu',
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'belum_bayar',
                'waktu_pesan'       => now(),
            ]);

            foreach ($details as $detail) {
                $pesanan->detailPesanan()->create($detail);
            }

            // Update terjual
            foreach ($cart as $item) {
                Menu::where('id', $item['menu_id'])->increment('terjual', $item['jumlah']);
            }

            DB::commit();

            // Clear cart
            session()->forget('keranjang');

            // Redirect berdasarkan metode pembayaran
            $redirectUrl = $request->metode_pembayaran === 'qris'
                ? route('pembayaran.index', $pesanan->kode_pesanan)
                : route('tracking.index', $pesanan->kode_pesanan);

            return response()->json([
                'success'      => true,
                'message'      => 'Pesanan berhasil dibuat!',
                'kode_pesanan' => $pesanan->kode_pesanan,
                'redirect'     => $redirectUrl,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
            ], 500);
        }
    }

    public function tracking(string $kodePesanan)
    {
        $pesanan = Pesanan::with(['meja', 'detailPesanan.menu'])
            ->where('kode_pesanan', $kodePesanan)
            ->firstOrFail();

        return view('customer.tracking.index', compact('pesanan'));
    }

    public function getStatus(string $kodePesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)->firstOrFail();

        $steps = [
            ['key' => 'menunggu',       'label' => 'Menunggu',       'icon' => 'bi-hourglass-split'],
            ['key' => 'diproses',       'label' => 'Diproses',       'icon' => 'bi-fire'],
            ['key' => 'siap_disajikan', 'label' => 'Siap Disajikan', 'icon' => 'bi-check2-circle'],
            ['key' => 'selesai',        'label' => 'Selesai',        'icon' => 'bi-emoji-smile'],
        ];

        $statusOrder = ['menunggu', 'diproses', 'siap_disajikan', 'selesai'];
        $currentIdx  = array_search($pesanan->status, $statusOrder);

        return response()->json([
            'status'            => $pesanan->status,
            'status_label'      => $pesanan->status_label,
            'status_color'      => $pesanan->status_color,
            'current_index'     => $currentIdx !== false ? $currentIdx : 0,
            'steps'             => $steps,
            'is_selesai'        => $pesanan->status === 'selesai',
            'is_dibatalkan'     => $pesanan->status === 'dibatalkan',
            'status_pembayaran' => $pesanan->status_pembayaran,
            'metode_pembayaran' => $pesanan->metode_pembayaran,
        ]);
    }

    public function bayar(string $kodePesanan)
    {
        $pesanan = Pesanan::with(['meja'])->where('kode_pesanan', $kodePesanan)->firstOrFail();
        if ($pesanan->status_pembayaran === 'lunas') {
            return redirect()->route('tracking.index', $kodePesanan);
        }
        return view('customer.payment.qris', compact('pesanan'));
    }

    public function scan(string $kodePesanan)
    {
        $pesanan = Pesanan::with(['meja'])->where('kode_pesanan', $kodePesanan)->firstOrFail();
        return view('customer.payment.scan', compact('pesanan'));
    }

    public function simulasiSukses(Request $request, string $kodePesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)->firstOrFail();
        
        DB::beginTransaction();
        try {
            $pesanan->update([
                'status_pembayaran' => 'lunas',
                'status' => $pesanan->status === 'menunggu' ? 'diproses' : $pesanan->status
            ]);
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimulasikan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran.'
            ], 500);
        }
    }

    public function getPaymentStatus(string $kodePesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)->firstOrFail();
        return response()->json([
            'status_pembayaran' => $pesanan->status_pembayaran
        ]);
    }
}
