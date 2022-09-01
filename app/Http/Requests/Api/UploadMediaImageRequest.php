<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class UploadMediaImageRequest extends ApiRequest
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
            'image' => 'required',
            'position' => 'required',
            'caption' => 'nullable|max:70'
           
        ];
    }


    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'position.required' => __('message.position_required'),
            'caption.required' => __('message.image_caption_max'),
            'image.image' => __('message.valid_image')
        ];
    }     
}
