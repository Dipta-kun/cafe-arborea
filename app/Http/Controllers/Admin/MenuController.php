<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $menus = Menu::with('kategori')->latest()->get()->map(function ($menu) {
                return [
                    'id'           => $menu->id,
                    'foto_url'     => $menu->foto_url,
                    'nama_menu'    => $menu->nama_menu,
                    'kategori'     => $menu->kategori?->nama_kategori,
                    'harga_format' => $menu->harga_format,
                    'stok'         => $menu->stok,
                    'is_tersedia'  => $menu->is_tersedia,
                    'terjual'      => $menu->terjual,
                ];
            });
            return response()->json(['data' => $menus]);
        }

        $kategori = Kategori::active()->get();
        return view('admin.menu.index', compact('kategori'));
    }

    public function store(MenuRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $base64 = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
            $data['foto'] = $base64;
        }

        $data['is_tersedia'] = $request->boolean('is_tersedia', true);
        $menu = Menu::create($data);
        $menu->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan.',
            'data'    => $menu,
        ]);
    }

    public function show(Menu $menu)
    {
        $menu->load('kategori');
        return response()->json(['data' => $menu]);
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($menu->foto && !str_starts_with($menu->foto, 'data:')) {
                Storage::disk('public')->delete($menu->foto);
            }
            $file = $request->file('foto');
            $base64 = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
            $data['foto'] = $base64;
        } else {
            unset($data['foto']);
        }

        $data['is_tersedia'] = $request->boolean('is_tersedia', true);
        $menu->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data'    => $menu->fresh(['kategori']),
        ]);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->foto && !str_starts_with($menu->foto, 'data:')) {
            Storage::disk('public')->delete($menu->foto);
        }
        $menu->delete();
        return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus.']);
    }

    public function toggle(Menu $menu)
    {
        $menu->update(['is_tersedia' => !$menu->is_tersedia]);
        return response()->json([
            'success'     => true,
            'message'     => 'Status menu diperbarui.',
            'is_tersedia' => $menu->is_tersedia,
        ]);
    }
}
