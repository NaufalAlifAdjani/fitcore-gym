<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePtSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'session_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'session_date.required' => 'Tanggal sesi wajib dipilih.',
            'session_date.after_or_equal' => 'Tanggal sesi tidak boleh di masa lampau.',
            'start_time.required' => 'Jam sesi wajib dipilih.',
            'start_time.regex' => 'Format jam sesi tidak valid (contoh: 14:00).',
        ];
    }
}
