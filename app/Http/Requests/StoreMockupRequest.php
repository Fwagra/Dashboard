<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreMockupRequest extends Request
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
        switch($this->method())
        {
            case 'POST':
            {
                return [
                    'name' => 'required|max:255',
                    'mockup_category_id' => 'required',
                    'images' => 'required|mimes:jpeg,jpg,png',
                    'psd' => 'mimes:psd',
                ];
            }
            case 'PUT':
            {
                return [
                    'name' => 'required|max:255',
                    'mockup_category_id' => 'required',
                    'images' => 'mimes:jpeg,jpg,png',
                    'psd' => 'mimes:psd',
                ];
            }
        }
    }
}
