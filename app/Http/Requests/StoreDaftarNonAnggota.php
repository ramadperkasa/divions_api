<?php

namespace App\Http\Requests;

use App\Rules\JenisKelamin;
use Illuminate\Foundation\Http\FormRequest;

class StoreDaftarNonAnggota extends FormRequest
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
            'nama_lengkap' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
            'nama_singkat' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
            'nama_perusahaan' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
            'pejabat_jabatan' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
            'provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
            'kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
            'kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
            'kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
            'alamat' => 'required|regex:/^[a-zA-Z0-9.\/()\- ]*$/',
            'email' => 'required|email|unique:mysql3.trn_pendaftaran|unique:mysql3.ref_pendaftar',
            'website' => 'nullable|url',
            'telp_no1' => 'required|numeric|unique:mysql3.ref_pendaftar',
            'telp_no2' => 'nullable|numeric|unique:mysql3.ref_pendaftar',
            'fax_no1' => 'nullable|numeric|unique:mysql3.ref_pendaftar',
            'fax_no2' => 'nullable|numeric|unique:mysql3.ref_pendaftar',
            'nik' => 'required|numeric|unique:mysql3.ref_pendaftar',
            'identitas_provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
            'identitas_kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
            'identitas_kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
            'identitas_kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
            'identitas_alamat' => 'required|regex:/^[a-zA-Z0-9.\/()\- ]*$/',
            'lahir_tempat' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
            'lahir_tanggal' => 'required',
            'jk' => ['required', new JenisKelamin()]
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Email sudah terdaftar'
        ];
    }
}
