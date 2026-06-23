<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PesananRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'meja_id'         => ['required', 'exists:meja,id'],
            'nama_pelanggan'  => ['required', 'string', 'max:100'],
            'catatan'         => ['nullable', 'string', 'max:500'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menu,id'],
            'items.*.jumlah'  => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function messages(): array
    {
        return [
            'meja_id.required'          => 'Meja wajib dipilih.',
            'nama_pelanggan.required'   => 'Nama pelanggan wajib diisi.',
            'items.required'            => 'Pesanan tidak boleh kosong.',
            'items.min'                 => 'Minimal 1 item dipesan.',
            'items.*.menu_id.required'  => 'Menu tidak valid.',
            'items.*.jumlah.required'   => 'Jumlah item wajib diisi.',
            'items.*.jumlah.min'        => 'Jumlah minimal 1.',
        ];
    }
}
