<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MejaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('meja');
        return [
            'nomor_meja' => ['required', 'string', 'max:10', 'unique:meja,nomor_meja,' . ($id ? $id->id : 'NULL')],
            'nama_meja'  => ['nullable', 'string', 'max:50'],
            'kapasitas'  => ['required', 'integer', 'min:1', 'max:20'],
            'status'     => ['required', 'in:tersedia,terisi,tidak_aktif'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_meja.required' => 'Nomor meja wajib diisi.',
            'nomor_meja.unique'   => 'Nomor meja sudah digunakan.',
            'kapasitas.required'  => 'Kapasitas wajib diisi.',
            'status.required'     => 'Status wajib dipilih.',
            'status.in'           => 'Status tidak valid.',
        ];
    }
}
