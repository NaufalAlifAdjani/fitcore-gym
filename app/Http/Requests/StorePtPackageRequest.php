<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePtPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'pt_session_count' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_membership_days' => ['required', 'integer', 'min:0'],
            'validity_days' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama paket',
            'pt_session_count' => 'jumlah sesi',
            'price' => 'harga',
            'min_membership_days' => 'syarat minimum hari membership',
            'validity_days' => 'masa berlaku sesi',
            'status' => 'status paket',
        ];
    }

    public function messages(): array
    {
        return [
            'pt_session_count.min' => 'Jumlah sesi minimal 1 sesi dan tidak boleh negatif.',
            'price.min' => 'Harga paket tidak boleh bernilai negatif.',
            'min_membership_days.min' => 'Syarat minimum hari membership tidak boleh bernilai negatif.',
            'validity_days.min' => 'Masa berlaku sesi minimal 1 hari.',
        ];
    }
}
