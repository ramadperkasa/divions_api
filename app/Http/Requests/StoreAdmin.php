<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmin extends FormRequest
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
        if ($this->isEdit) {
            return [
                'form.email' => 'required|email',
                'form.name' => 'required'
            ];
        } else {
            return [
                'form.email' => 'required|email|unique:mysql.users,email',
                'form.name' => 'required',
                'form.password' => 'required|min:6',
                'form.c_password' => 'required|same:form.password'
            ];
        }
    }
}
