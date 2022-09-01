<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class ProfileJourneyRequest extends ApiRequest
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
            'journey' => 'required|string|max:2000',
        ];
    }

    
    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'journey.required' => __('message.journey_required'),
            'journey.max' => __('message.journey_max')
        ];
    } 
}
