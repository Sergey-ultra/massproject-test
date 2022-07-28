<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'message' => 'required|string|min:3|max:1000',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
