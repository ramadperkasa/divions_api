<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreVacancy extends FormRequest
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
        if ($request->duplicate) {
            return [
                'judul' => 'required',
                'kategori_sub_vacancy_id' => 'required',
                'isi_berita' => 'required',

                'kategori_sub_vacancy_id' => 'required',
                'tgl_expired' => 'required',
            ];
        } else if (!$request->isreorder) {
            return [
                'judul' => 'required',
                'kategori_sub_vacancy_id' => 'required',
                'isi_berita' => 'required',
                'kategori_vacancy' => 'required',
                'kategori_sub_vacancy_id' => 'required',
                'tgl_expired' => 'required',
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
