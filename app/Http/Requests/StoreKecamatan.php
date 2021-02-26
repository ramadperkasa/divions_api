<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKecamatan extends FormRequest
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
            'id' => 'required|digits:7',
            'provinsi_id' => 'required',
            'nama_kecamatan' => 'required',
            'kabupaten_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id.digits' => 'ID Harus Berupa angka dan 7 digit',
            'id.required' => 'ID Kecamatan Tidak Boleh Kosong !',
            'provinsi_id.required' => 'Provinsi Tidak Boleh Kosong !',
            'nama_kecamatan' => 'Nama Kecamatan Tidak Boleh Kosong !',
            'kabupaten_id' => 'Kabupaten Tidak Boleh Kosong !'
        ];
    }
}
