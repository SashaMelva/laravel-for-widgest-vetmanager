<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostNewApiSetting extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'domainName' => 'required|string|max:100',
            'apiKey' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'domainName' => 'A domain name is not valid',
            'apiKey' => 'A api key is not valid',
        ];
    }
}
