<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class ProfileImageRequest extends ApiRequest
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
            'profile_image' => 'nullable|image',
            'banner_image' => 'nullable|image',
        ];
    }
   
    /**
     * Custom validation messages
     */
    public function messages() {
        return [
            'profile_image.image' => __('message.valid_profile_image_required'),
            'banner_image.image' => __('message.valid_profile_cover_image_required'),
        ];
    }
}
