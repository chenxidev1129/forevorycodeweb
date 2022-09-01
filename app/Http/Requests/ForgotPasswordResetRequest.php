<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordResetRequest extends FormRequest
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
            'password' => 'required|min:6|max:20|regex:'.config('constants.Regex.PASSWORD'),
            'password_confirmation' => 'required|same:password',
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
           
        ];
    }
}
