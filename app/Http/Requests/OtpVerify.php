<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerify extends FormRequest
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
            'otp' => 'required|max:6'
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
        ];
    }
}
