<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'phone' => ['required', 'string', 'min:10', 'max:20', function ($attribute, $value, $fail) {
                $digits = preg_replace('/\D+/', '', $value ?? '');

                if (strlen($digits) < 10 || strlen($digits) > 15) {
                    $fail('Enter a valid phone number (10–15 digits).');
                }
            }],
            'product' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : null,
            'phone' => is_string($this->phone) ? trim($this->phone) : null,
            'product' => is_string($this->product) ? trim($this->product) : null,
            'message' => is_string($this->message) ? trim($this->message) : null,
        ]);
    }
}
