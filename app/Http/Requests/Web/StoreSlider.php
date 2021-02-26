<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlider extends FormRequest
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
        if (!$this->sliderAction) {
            return [                
                'image_url' => 'required',
            ];
        } else if ($this->tipe_link == 1) {
            return [                
                'image_url' => 'required',
                'target' => 'required',
                'tipe_link' => 'required',
                'url' => 'required'
            ];
        } else if ($this->tipe_link == 2) {
            return [                
                'image_url' => 'required',
                'url' => 'required|url',
                'target' => 'required',
                'tipe_link' => 'required'
            ];
        } else if ($this->tipe_link == 3) {
            return [                
                'image_url' => 'required',
                'url' => 'required',
                'block_template_id' => 'required',
                'target' => 'required',
                'tipe_link' => 'required'
            ];
        } else {
            return [                
                'target' => 'required',
                'tipe_link' => 'required',
                'berita_id' => 'required'
            ];
        }
    }
}
