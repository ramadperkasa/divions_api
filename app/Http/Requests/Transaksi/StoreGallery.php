<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class StoreGallery extends FormRequest
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
            'id_kategori' => 'required',
            'image' => 'required',
            'judul' => 'required'

        ];
    }
    public function messages()
    {
        return [
            'id_kategori.required' => 'Kategori Harus Diisi !',
            'image.required' => 'Cover Gallery Harus Diisi !',
            'judul.required' => 'Judul Harus Diisi !'
        ];
    }
}
