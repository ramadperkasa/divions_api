<?php

namespace App\Http\Requests;

use App\Rules\JenisKelamin;
use App\Rules\OldPassword;
use App\Rules\Unik;
use Illuminate\Foundation\Http\FormRequest;

class StoreProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->page == 1) {
            if (auth('api')->user()->anggota) {
                if (auth('api')->user()->anggota->anggotaJenis->jenis == 0) {
                    return [
                        'nama_lengkap' => 'required',
                        'nama_singkat' => 'required',
                        'pejabat_jabatan' => 'required'
                    ];
                } else {
                    return [
                        'nama_lengkap' => 'required',
                        'nama_singkat' => 'required',
                        'pejabat_jabatan' => 'required',
                        'jk' => ['required', new JenisKelamin()],
                        'lahir_tempat' => 'required',
                        'lahir_tanggal' => 'required',
                    ];
                }
            } else {
                return [
                    'nama_lengkap' => 'required',
                    'nama_singkat' => 'required',
                    'pejabat_jabatan' => 'required',
                    'jk' => ['required', new JenisKelamin()],
                    'lahir_tempat' => 'required',
                    'lahir_tanggal' => 'required',
                ];
            }
        } else if ($this->page == 2) {
            return [
                'identitas_provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
                'identitas_kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
                'identitas_kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
                'identitas_kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
                'identitas_alamat' => 'required',
            ];
        } else if ($this->page == 3) {
            return [
                'provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
                'kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
                'kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
                'kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
                'alamat' => 'required',
            ];
        } else if ($this->page == 4) {
            return [
                'website' => 'nullable|url',
                'telp_no1' => ['required', 'numeric', new Unik('telp_no1')],
                'telp_no2' => ['nullable', 'numeric', new Unik('telp_no2')],
                'fax_no1' => ['nullable', 'numeric', new Unik('fax_no1')],
                'fax_no2' => ['nullable', 'numeric', new Unik('fax_no2')],
            ];
        } else if ($this->page == 5) {
            return [
                'oldPassword' => ['required', new OldPassword(auth('api')->user()->password)],
                'password' => 'required|min:6|different:oldPassword',
                'c_password' => 'required|same:password'
            ];
        } else {
            return [
                'logo_big' => 'required'
            ];
        }
    }
}
