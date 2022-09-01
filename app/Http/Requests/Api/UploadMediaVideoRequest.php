<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class UploadMediaVideoRequest extends ApiRequest
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
            'position' => 'required',
            'caption' => 'nullable|max:70',
            'duration' => 'required',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'position.required' => __('message.position_required'),
            'caption.max' => __('message.image_caption_max'),
            'duration.required' => __('message.duration_required'),
        ];
    } 
}
