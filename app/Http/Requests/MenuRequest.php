<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('menu');
        $rules = [
            'kategori_id' => ['required', 'exists:kategori,id'],
            'nama_menu'   => ['required', 'string', 'max:150', 'unique:menu,nama_menu,' . ($id ? $id->id : 'NULL')],
            'deskripsi'   => ['nullable', 'string', 'max:1000'],
            'harga'       => ['required', 'numeric', 'min:0'],
            'stok'        => ['required', 'integer', 'min:0'],
            'is_tersedia' => ['boolean'],
        ];

        $rules['foto'] = ['nullable', 'file', 'extensions:jpg,jpeg,png,webp,gif,svg,bmp,heic,heif,jfif,tiff,tif,ico', 'max:20480'];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori tidak valid.',
            'nama_menu.required'   => 'Nama menu wajib diisi.',
            'nama_menu.unique'     => 'Nama menu sudah digunakan.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'stok.required'        => 'Stok wajib diisi.',
            'foto.file'            => 'File foto harus berupa file yang valid.',
            'foto.extensions'      => 'Format foto harus berupa jpg, jpeg, png, webp, gif, svg, bmp, heic, heif, jfif, tiff, tif, atau ico.',
            'foto.max'             => 'Ukuran foto maksimal 20MB.',
        ];
    }
}
