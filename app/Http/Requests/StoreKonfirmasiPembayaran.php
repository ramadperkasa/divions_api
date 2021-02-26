<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKonfirmasiPembayaran extends FormRequest
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
        $jenis = $this->jenis;

        if ($jenis == 1) {
            $jenis = 'exists:mysql2.trn_pendaftaran,id';
            $kode_unik = 'required|exists:mysql2.trn_pendaftaran,kode_unik';
        } else {
            $jenis = 'status_anggota:jenis';

            $kode_unik = 'nullable';
        }

        if ($jenis == 2) {
            $pelatihan_id = 'required|exists.mysql2.trn_pelatihan,judul';
        } else {
            $pelatihan_id = 'nullable';
        }


        return [
            'jenis' => 'nullable',
            'kode_unik' => $kode_unik,
            'pendaftar_id' => 'required|' . $jenis,
            'pelatihan_id' => $pelatihan_id,
            'sumber_bank_id' => 'required|exists:mysql2.ref_bank,id',
            'sumber_rekening_no' => 'required|numeric',
            'sumber_rekening_nama' => 'required',
            'tujuan_rekening_id' => 'required|exists:mysql2.ref_rekening_bank,id',
            'tgl_pembayaran' => 'required|date',
            'jumlah' => 'required|numeric',
            'bukti_transfer' => 'required|mimes:png,jpg,jpeg'
        ];
    }
}
