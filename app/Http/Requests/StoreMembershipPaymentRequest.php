<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bank_id' => ['required', 'exists:banks,id'],
            'sender_name' => ['required', 'string', 'max:255'],
            'proof_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'bank_id.required' => 'Silakan pilih bank tujuan transfer.',
            'bank_id.exists' => 'Bank tujuan transfer tidak valid.',
            'sender_name.required' => 'Nama pemilik rekening wajib diisi.',
            'proof_image.required' => 'Bukti transfer wajib diunggah.',
            'proof_image.image' => 'File bukti transfer harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, webp.',
            'proof_image.max' => 'Ukuran file bukti transfer maksimal 2MB.',
        ];
    }
}
