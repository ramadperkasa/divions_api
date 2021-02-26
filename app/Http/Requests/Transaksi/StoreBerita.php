<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreBerita extends FormRequest
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
    public function rules(Request $request)
    {
        if (!$request->isreorder) {
            return [
                'judul' => 'required',
                'sinopsis' => 'required',
                'id_kategori' => 'required',
                'isi_berita' => 'required',
            ];
        } else {
            return [];
        }
    }

    public function messages()
    {
        return [];
    }
}
