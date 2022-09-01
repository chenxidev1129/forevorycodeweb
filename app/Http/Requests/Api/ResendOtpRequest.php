<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class ResendOtpRequest extends ApiRequest
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
            'email' => 'required|regex:'. config('constants.Regex.EMAIL'),
            'email_type' => 'required'
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'email.required' => __('message.your_email_required'),
            'email.regex' => __('message.email_regex'),
            'email_type.required' =>  __('message.email_type_required'),
        ];
    }  
}
