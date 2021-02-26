<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKabupaten extends FormRequest
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
            'id' => 'required|digits:4',
            'nama_kabupaten' => 'required',
            'provinsi_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id.digits' => 'ID Harus Berupa angka dan 4 digit',
            'id.required' => 'ID Kabupaten Tidak Boleh Kosong !',
            'nama_kabupaten.required' => 'Nama Kabupaten Tidak Boleh Kosong !',
            'provinsi_id.required' => 'Provinsi Tidak Boleh Kosong !'
        ];
    }
}
