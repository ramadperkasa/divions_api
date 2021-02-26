<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class StoreAgendaDetail extends FormRequest
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
            'ruangan_id' => 'required',
            'tgl' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'nama_penanggung_jawab' => 'required',
            'no_hp_penanggung_jawab' => 'required',
            'jumlah_jamaah' => 'required',
            'judul_acara' => 'required',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}