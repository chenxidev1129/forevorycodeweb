<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class AddUpdateGraveImageRequest extends ApiRequest
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
            'image' => 'nullable|image',
        ];
    }
        
    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'image.image' => __('message.valid_grave_image_only'),   
        ];
    }
}
