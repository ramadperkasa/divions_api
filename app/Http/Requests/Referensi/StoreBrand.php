<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrand extends FormRequest
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
            'nama_brand' => 'required',
            'brand_kategori_id' => 'required',
            'url' => 'url|nullable'            
        ];
    }

    public function messages()
    {
        return [
            'brand_kategori_id.required' => 'brand kategori wajib diisi.',
        ];
    }
}
