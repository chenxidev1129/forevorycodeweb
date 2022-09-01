<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class PasswordOtpVerificationRequest extends ApiRequest
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
            'otp' => 'required|min:5|max:6'
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'otp.required' =>  __('message.otp_required'),
            'otp.max' => __('message.otp_max'),
            'otp.min' => __('message.otp_min'),
            'email.required' => __('message.your_email_required'),
            'email.regex' => __('message.email_regex'),
        ];
    }   
}
