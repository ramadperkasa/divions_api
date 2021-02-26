<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreRekeningBank extends FormRequest
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
            'bank_id' => 'required',
            'cabang' => 'required',
            'rekening_no' => 'numeric|digits_between:0,20',
            'rekening_nama' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'bank_id.required' => 'Bank Tidak Boleh Kosong !',
            'cabang.required' => 'Cabang Tidak Boleh Kosong !',
            'rekening_nama.required' => 'Nama Rekening Tidak Boleh Kosong !'
        ];
    }
}
