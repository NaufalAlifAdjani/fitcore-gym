<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:50'],
            'tier' => ['required', 'in:Basic,Standard,Premium & VIP'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'promo_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'duration_value' => ['required', 'integer', 'min:1'],
            'duration_unit' => ['required', 'in:Hari,Bulan,Tahun'],
            'duration_in_days' => ['required', 'integer', 'min:1'],
            'facilities' => ['required', 'array', 'min:1'],
            'facilities.*' => ['required', 'string', 'max:255'],
            'pt_sessions' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
