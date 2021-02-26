<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class StoreInvestor extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'cover_image' => 'required',
            'investor_name' => 'required',
            'url' => 'required|url',
        ];
    }

    public function messages()
    {
        return [];
    }
}
