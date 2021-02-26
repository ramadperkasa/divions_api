<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelurahan extends FormRequest
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
            'id' => 'required|digits:10',
            'nama_kelurahan' => 'required',
            'provinsi_id' => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id.digits' => 'ID Harus Berupa angka dan 10 digit',
            'id.required' => 'ID Kelurahan Tidak Boleh Kosong !',
            'nama_kelurahan.required' => 'Nama Kelurahan Tidak Boleh Kosong !',
            'provinsi_id.required' => 'Provinsi Tidak Boleh Kosong !',
            'kabupaten_id.required' => 'Kabupaten / Kota Tidak Boleh Kosong !',
            'kecamatan_id.required' => 'Kecamatan Tidak Boleh Kosong !'
        ];
    }
}
