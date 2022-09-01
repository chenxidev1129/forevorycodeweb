<?php

namespace App\Http\Requests\Api;
use App\Http\Requests\Api\ApiRequest;

class LoginRequest extends ApiRequest
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
            //'device_token' => 'required',
            'password' => 'required',
            'email' => 'required|regex:'. config('constants.Regex.EMAIL'),
        ];
    }

    /**
     * user login validation messages
     */
    public function messages()
    {
        return [
            'device_token.device_token' => __('message.device_token_required'),
            'email.required' => __('message.email_required'),
            'email.regex' => __('message.email_regex'),
            'password.required' => __('message.password_required'),
        ];
    } 
}
