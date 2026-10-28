<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyPtPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only authenticated users with role 'member' can purchase PT packages.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'member';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pt_package_id' => ['required', 'integer', 'exists:pt_packages,id'],
            'bank_id'       => ['required', 'integer', 'exists:banks,id'],
            'proof_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'pt_package_id.required' => 'Pilih paket PT yang ingin dibeli.',
            'pt_package_id.integer'  => 'Format ID paket PT tidak valid.',
            'pt_package_id.exists'   => 'Paket PT yang dipilih tidak ditemukan.',
            'bank_id.required'       => 'Pilih bank tujuan pembayaran.',
            'bank_id.integer'        => 'Format ID bank tidak valid.',
            'bank_id.exists'         => 'Bank tujuan pembayaran tidak valid.',
            'proof_image.image'      => 'Bukti pembayaran harus berupa gambar.',
            'proof_image.mimes'      => 'Format bukti pembayaran harus JPG, JPEG, PNG, atau WEBP.',
            'proof_image.max'        => 'Ukuran bukti pembayaran maksimal 2MB.',
        ];
    }

    /**
     * Return 403 when authorization fails.
     */
    protected function failedAuthorization(): never
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            'Hanya member gym yang dapat membeli paket Personal Trainer.'
        );
    }
}
