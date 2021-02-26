<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreMitra extends FormRequest
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
            'nama' => 'required',
            'url' => 'required|url'
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Mitra Harus Diisi !',
            'url.required' => 'URL Harus Diisi !'
        ];
    }
}
