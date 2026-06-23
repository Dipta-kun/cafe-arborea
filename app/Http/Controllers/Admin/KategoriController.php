<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $kategori = Kategori::withCount('menu')->orderBy('urutan')->get();
            return response()->json(['data' => $kategori]);
        }
        return view('admin.kategori.index');
    }

    public function store(KategoriRequest $request)
    {
        $kategori = Kategori::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data'    => $kategori,
        ]);
    }

    public function show(Kategori $kategori)
    {
        return response()->json(['data' => $kategori]);
    }

    public function update(KategoriRequest $request, Kategori $kategori)
    {
        $kategori->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => $kategori->fresh(),
        ]);
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->menu()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki menu.',
            ], 422);
        }
        $kategori->delete();
        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
    }

    public function toggle(Kategori $kategori)
    {
        $kategori->update(['is_active' => !$kategori->is_active]);
        return response()->json([
            'success' => true,
            'message' => 'Status kategori diperbarui.',
            'is_active' => $kategori->is_active,
        ]);
    }
}
