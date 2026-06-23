<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    private function getCart(): array
    {
        return session('keranjang', []);
    }

    private function saveCart(array $cart): void
    {
        session(['keranjang' => $cart]);
    }

    public function index()
    {
        $cart  = $this->getCart();
        $total = collect($cart)->sum(fn($item) => $item['subtotal']);

        return response()->json([
            'items' => array_values($cart),
            'total' => $total,
            'total_format' => 'Rp ' . number_format($total, 0, ',', '.'),
            'count' => array_sum(array_column($cart, 'jumlah')),
        ]);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'menu_id' => ['required', 'exists:menu,id'],
            'jumlah'  => ['required', 'integer', 'min:1'],
        ]);

        $menu = Menu::findOrFail($request->menu_id);

        if (!$menu->is_tersedia || $menu->stok <= 0) {
            return response()->json(['success' => false, 'message' => 'Menu tidak tersedia.'], 422);
        }

        $cart = $this->getCart();
        $key  = 'menu_' . $menu->id;

        if (isset($cart[$key])) {
            $cart[$key]['jumlah']  += $request->jumlah;
            $cart[$key]['subtotal'] = $cart[$key]['jumlah'] * $menu->harga;
        } else {
            $cart[$key] = [
                'id'           => $key,
                'menu_id'      => $menu->id,
                'nama_menu'    => $menu->nama_menu,
                'harga'        => (float) $menu->harga,
                'harga_format' => $menu->harga_format,
                'jumlah'       => $request->jumlah,
                'subtotal'     => (float) $menu->harga * $request->jumlah,
                'foto_url'     => $menu->foto_url,
            ];
        }

        $this->saveCart($cart);

        $total = collect($cart)->sum(fn($item) => $item['subtotal']);

        return response()->json([
            'success'      => true,
            'message'      => $menu->nama_menu . ' ditambahkan ke keranjang.',
            'count'        => array_sum(array_column($cart, 'jumlah')),
            'total_format' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['jumlah' => ['required', 'integer', 'min:0']]);

        $cart = $this->getCart();

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan.'], 404);
        }

        if ($request->jumlah == 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['jumlah']  = $request->jumlah;
            $cart[$id]['subtotal'] = $cart[$id]['harga'] * $request->jumlah;
        }

        $this->saveCart($cart);
        $total = collect($cart)->sum(fn($item) => $item['subtotal']);

        return response()->json([
            'success'      => true,
            'items'        => array_values($cart),
            'total'        => $total,
            'total_format' => 'Rp ' . number_format($total, 0, ',', '.'),
            'count'        => array_sum(array_column($cart, 'jumlah')),
        ]);
    }

    public function hapus(string $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);

        $total = collect($cart)->sum(fn($item) => $item['subtotal']);

        return response()->json([
            'success'      => true,
            'message'      => 'Item dihapus dari keranjang.',
            'items'        => array_values($cart),
            'total'        => $total,
            'total_format' => 'Rp ' . number_format($total, 0, ',', '.'),
            'count'        => array_sum(array_column($cart, 'jumlah')),
        ]);
    }

    public function kosongkan()
    {
        session()->forget('keranjang');
        return response()->json(['success' => true, 'message' => 'Keranjang dikosongkan.']);
    }

    public function count()
    {
        $cart  = $this->getCart();
        $total = collect($cart)->sum(fn($item) => $item['subtotal']);
        return response()->json([
            'count'        => array_sum(array_column($cart, 'jumlah')),
            'total_format' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }
}
