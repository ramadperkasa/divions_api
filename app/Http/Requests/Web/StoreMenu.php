<?php

namespace App\Http\Requests\Web;

// use App\Rules\CheckLink;
use Illuminate\Foundation\Http\FormRequest;

class StoreMenu extends FormRequest
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
        if ($this->has_child == 2) {
            if ($this->tipe_link == 1) {
                return [
                    'title' => 'required|max:50',
                    'title_en' => 'nullable|max:50',
                    'target' => 'required',
                    // 'url' => ['required', new CheckLink()],
                ];
            } else if ($this->tipe_link == 2) {
                return [
                    'title' => 'required|max:50',
                    'title_en' => 'nullable|max:50',
                    'target' => 'required',
                    'url' => 'required|url',
                ];
            } else if ($this->tipe_link == 3) {
                return [
                    'title' => 'required|max:50',
                    'title_en' => 'nullable|max:50',
                    'target' => 'required',
                    'url' => 'required',
                ];
            } else if ($this->tipe_link == 3) {
                return [
                    'title' => 'required|max:50',
                    'title_en' => 'nullable|max:50',
                    'target' => 'required',
                    'url' => 'required',
                    'brand_id' => 'required',
                ];
            } else {
                return [
                    'title' => 'required|max:50',
                    'title_en' => 'nullable|max:50',
                    'target' => 'required',
                ];
            }
        } else {
            return [
                'title' => 'required|max:50',
                'title_en' => 'nullable|max:50',
                'target' => 'required',
            ];
        }
    }
}
