<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicker extends FormRequest
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
            'title' => 'required',
            'url' => 'required',
            'target' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Judul Harus Diisi !',
            'url.required' => 'URL Harus Diisi !',
            'target.required' => 'Aksi Link Harus Diisi !',
        ];
    }
}
