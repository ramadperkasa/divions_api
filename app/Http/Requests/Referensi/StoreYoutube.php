<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreYoutube extends FormRequest
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
            'image' => 'required',
            'judul' => 'required',
            'sinopsis' => 'required',
            'url' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'image.required' => 'Cover Image Url Harus Diisi !',
            'judul.required' => 'Judul Harus Diisi !',
            'sinopsis.required' => 'Sinopsis Harus Diisi !',
            'url.required' => 'Url Harus Diisi !'
        ];
    }
}
