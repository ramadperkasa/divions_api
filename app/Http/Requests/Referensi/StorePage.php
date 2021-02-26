<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StorePage extends FormRequest
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
            'judul' => 'required',
            'konten' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'judul.required' => 'Judul Harus Diisi !',
            'konten.required' => 'Konten Harus Diisi !'

        ];
    }
}
