<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreBank extends FormRequest
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
        // |exists:mysql2.ref_anggota_tipe,id
        return [
            'kode' => 'required',
            'nama' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'kode.required' => 'Kode harus diisi !',
            'nama.required' => 'Nama harus diisi !',
        ];
    }
}
