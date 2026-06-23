<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Meja;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(string $nomorMeja)
    {
        $meja = Meja::where('nomor_meja', $nomorMeja)->firstOrFail();

        if ($meja->status === 'tidak_aktif') {
            abort(404, 'Meja tidak tersedia.');
        }

        $kategori = Kategori::active()->withCount(['menu' => function ($q) {
            $q->where('is_tersedia', true);
        }])->get();

        // Store meja_id in session for cart
        session(['meja_id' => $meja->id, 'nomor_meja' => $meja->nomor_meja]);

        return view('customer.menu.index', compact('meja', 'kategori'));
    }

    public function getData(Request $request, string $nomorMeja)
    {
        $meja  = Meja::where('nomor_meja', $nomorMeja)->firstOrFail();
        $query = Menu::with('kategori')->where('is_tersedia', true);

        if ($request->kategori_id && $request->kategori_id !== 'all') {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->search) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        $menus = $query->orderBy('nama_menu')->get()->map(function ($menu) {
            return [
                'id'           => $menu->id,
                'nama_menu'    => $menu->nama_menu,
                'deskripsi'    => $menu->deskripsi,
                'harga'        => $menu->harga,
                'harga_format' => $menu->harga_format,
                'stok'         => $menu->stok,
                'foto_url'     => $menu->foto_url,
                'is_tersedia'  => $menu->is_tersedia,
                'kategori'     => $menu->kategori?->nama_kategori,
            ];
        });

        return response()->json(['data' => $menus]);
    }
}
