<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostNewPetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'alias' => 'required|string|max:100',
            'type-pet' => 'required|string|max:100',
            'breed' => 'nullable|string|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'alias' => 'A alias pet is not valid',
            'type-pet' => 'A type pet is not valid',
            'breed' => 'A breed pet is not valid',
        ];
    }
}
