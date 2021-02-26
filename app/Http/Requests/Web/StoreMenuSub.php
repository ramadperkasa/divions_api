<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuSub extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return auth('api')->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->tipe_link == 2) {
            return [
                'title' => 'required',
                'title_en' => 'nullable',
                'parent_id' => 'required',
                'tipe_link' => 'required',
                'url' => 'required|url'

            ];
        } else if ($this->tipe_link == 1) {
            return [
                'title' => 'required',
                'title_en' => 'nullable',
                'parent_id' => 'required',
                'tipe_link' => 'required',
                'url' => 'required'
            ];
        } else if ($this->tipe_link == 3) {
            return [
                'title' => 'required',
                'title_en' => 'nullable',
                'parent_id' => 'required',
                'tipe_link' => 'required',
                'kategori_id' => 'required',
            ];
        } else {
            return [
                'title' => 'required',
                'title_en' => 'nullable',
                'parent_id' => 'required',
                'tipe_link' => 'required',
                'url' => 'required'
            ];
        }
    }
}
