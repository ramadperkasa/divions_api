<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class StoreSettingBooking extends FormRequest
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
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
