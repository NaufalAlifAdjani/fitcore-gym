<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Backend authorization: only admin or trainer (petugas) roles.
     */
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'trainer']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'qr_token' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'qr_token.required' => 'Token QR Code diperlukan.',
            'qr_token.string'   => 'Format token QR Code tidak valid.',
        ];
    }

    /**
     * Return a 403 JSON response when authorization fails.
     * Overrides the default redirect to match the JSON API contract.
     */
    protected function failedAuthorization(): never
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            'Anda tidak memiliki akses untuk melakukan check-in.'
        );
    }
}
