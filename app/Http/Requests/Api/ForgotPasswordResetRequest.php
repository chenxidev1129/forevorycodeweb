<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class ForgotPasswordResetRequest extends ApiRequest
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
            'password_confirmation' => 'required|same:password',
            'password' => 'required|min:6|max:20|regex:'.config('constants.Regex.PASSWORD')
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'password.required' => __('message.new_password_required'),
            'password.min' => __('message.password_min'),
            'password.max' => __('message.password_max'),
            'password.regex' => __('message.password_regex'),
            'password_confirmation.required' => 'Please enter confirm password',
            'password_confirmation.same' => __('message.password_confirmed'),
            'email.required' => __('message.your_email_required'),
            'email.regex' => __('message.email_regex'),
           
        ];
    }
}
