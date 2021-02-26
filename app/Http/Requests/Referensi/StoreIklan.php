<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreIklan extends FormRequest
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
        // if ($this->type != null) {
        return [
            'foto_iklan' => 'required',
            'nama' => 'required',
            'type' => 'required'
        ];
        // } else {
        //     return [
        //     ];
        // }
    }
}
