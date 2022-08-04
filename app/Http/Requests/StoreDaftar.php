<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Referensi\AnggotaJenis;
use App\Model\Referensi\KelengkapanDetail;
use App\Rules\Extension;
use App\Rules\JenisKelamin;
use App\Rules\Sifat;
use App\Rules\CekNik;

class StoreDaftar extends FormRequest
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
        $jenis = AnggotaJenis::where('id', $this->anggota_jenis_id)->pluck('jenis')->first();

        if ($jenis == '0') {
            return [
                'anggota_jenis_id' => 'required|exists:mysql3.ref_anggota_jenis,id',
                'nama_lengkap' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
                'nama_singkat' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
                'pejabat_nama' => 'required|regex:/^[a-zA-Z.\/\- ]*$/',
                'pejabat_jabatan' => 'required|regex:/^[a-zA-Z.\/\- ]*$/',
                'provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
                'kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
                'kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
                'kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
                'identitas_provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
                'identitas_kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
                'identitas_kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
                'identitas_kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
                'alamat' => 'required|regex:/^[a-zA-Z0-9.\/()\- ]*$/',
                'identitas_alamat' => 'required|regex:/^[a-zA-Z0-9.\/()\- ]*$/',
                'email' => 'required|email|unique:mysql3.trn_pendaftaran|unique:mysql3.ref_pendaftar',
                'email_pic' => 'required|email',
                'website' => 'nullable|url',
                'telp_no1' => 'required|numeric|unique:mysql3.trn_pendaftaran',
                'telp_no2' => 'nullable|numeric|unique:mysql3.trn_pendaftaran',
                'fax_no1' => 'nullable|numeric|unique:mysql3.trn_pendaftaran',
                'fax_no2' => 'nullable|numeric|unique:mysql3.trn_pendaftaran',
                'nib' => 'required',
                'kelengkapan.*.nomor' => [new Sifat($this->anggota_jenis_id, $this->kelengkapan_id)],
                'kelengkapan.*.file_path.originalName' => [new Sifat($this->anggota_jenis_id, $this->kelengkapan_id), 'nullable|mimes:jpg,jpeg,gif,png,rar,zip,docx,doc,xlsx,xls']
            ];
        } else {
            return [
                'anggota_jenis_id' => 'required|exists:mysql3.ref_anggota_jenis,id',
                'nama_lengkap' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
                'nama_singkat' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
                'jk' => ['required', new JenisKelamin()],
                'lahir_tempat' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
                'lahir_tanggal' => 'required',
                'perusahaan_id' => 'required|exists:mysql3.ref_anggota,nama_lengkap',
                'pejabat_jabatan' => 'required|regex:/^[a-zA-Z.\/()\- ]*$/',
                'provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
                'kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
                'kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
                'kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
                'alamat' => 'required|regex:/^[a-zA-Z0-9.\/()\- ]*$/',
                'identitas_provinsi_id' => 'required|exists:mysql3.ref_provinsi,id',
                'identitas_kabupaten_id' => 'required|exists:mysql3.ref_kabupaten,id',
                'identitas_kecamatan_id' => 'required|exists:mysql3.ref_kecamatan,id',
                'identitas_kelurahan_id' => 'required|exists:mysql3.ref_kelurahan,id',
                'identitas_alamat' => 'required|regex:/^[a-zA-Z0-9.\/()\- ]*$/',
                'email' => 'required|email|unique:mysql3.trn_pendaftaran',
                'email_pic' => 'required|email',
                'website' => 'nullable|url',
                'telp_no1' => 'required|numeric|unique:mysql3.trn_pendaftaran',
                'telp_no2' => 'nullable|numeric|unique:mysql3.trn_pendaftaran',
                'fax_no1' => 'nullable|numeric|unique:mysql3.trn_pendaftaran',
                'fax_no2' => 'nullable|numeric|unique:mysql3.trn_pendaftaran',
                'nib' => 'required',
                'kelengkapan.*.kelengkapan_id' => [new CekNik($this->kelengkapan)],
                'kelengkapan.*.nomor' => [new Sifat($this->anggota_jenis_id, $this->kelengkapan_id)],
                'kelengkapan.*.file_path' => [new Sifat($this->anggota_jenis_id, $this->kelengkapan_id), new Extension]
            ];
        }
    }
}
