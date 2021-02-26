<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreContact extends FormRequest
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
        if ($this->jenis == 1 || $this->jenis == 2 || $this->jenis == 3 || $this->jenis == 9 || $this->jenis == 0) {
            return [
                'isi' => 'required',
                'jenis' => 'required',
                'url' => 'required|url'
            ];
        } else if ($this->jenis == 4 || $this->jenis == 5 || $this->jenis == 7 || $this->jenis == 8) {
            return [
                'isi' => 'required',
                'jenis' => 'required',
                'kolom1' => 'required'
            ];
        } else if ($this->jenis == 6) {
            return [
                'isi' => 'required',
                'jenis' => 'required',
                'lat' => 'required',
                'long' => 'required'
            ];
        } else {
            return [
                'isi' => 'required',
                'jenis' => 'required',
            ];
        }
    }
}
