<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_menu'       => Menu::count(),
            'total_kategori'   => Kategori::where('is_active', true)->count(),
            'total_pesanan'    => Pesanan::count(),
            'pendapatan_hari'  => Pesanan::hariIni()->whereIn('status', ['selesai', 'siap_disajikan'])->sum('total_harga'),
            'pesanan_aktif'    => Pesanan::whereNotIn('status', ['selesai', 'dibatalkan'])->count(),
            'menu_habis'       => Menu::where('stok', 0)->count(),
        ];

        $pesananTerbaru = Pesanan::with(['meja', 'detailPesanan'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'pesananTerbaru'));
    }

    public function chartPenjualanBulanan()
    {
        $data = Pesanan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->whereIn('status', ['selesai', 'siap_disajikan'])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $values = array_fill(0, 12, 0);
        foreach ($data as $item) {
            $values[$item->bulan - 1] = (float) $item->total;
        }

        return response()->json(['labels' => $labels, 'data' => $values]);
    }

    public function chartMenuTerlaris()
    {
        $data = DetailPesanan::select('menu_id', 'nama_menu_snapshot', DB::raw('SUM(jumlah) as total_terjual'))
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->whereIn('pesanan.status', ['selesai', 'siap_disajikan'])
            ->groupBy('menu_id', 'nama_menu_snapshot')
            ->orderByDesc('total_terjual')
            ->take(8)
            ->get();

        return response()->json([
            'labels' => $data->pluck('nama_menu_snapshot'),
            'data'   => $data->pluck('total_terjual'),
        ]);
    }

    public function chartPesananHarian()
    {
        $data = Pesanan::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return response()->json([
            'labels' => $data->pluck('tanggal'),
            'data'   => $data->pluck('total'),
        ]);
    }
}
