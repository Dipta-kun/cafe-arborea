<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('kategori');
        return [
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori,nama_kategori,' . ($id ? $id->id : 'NULL')],
            'deskripsi'     => ['nullable', 'string', 'max:500'],
            'icon'          => ['nullable', 'string', 'max:50'],
            'is_active'     => ['boolean'],
            'urutan'        => ['integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah digunakan.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
        ];
    }
}
