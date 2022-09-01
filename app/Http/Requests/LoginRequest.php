<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password' => 'required|regex:'.config('constants.Regex.PASSWORD'),
        ];
    }

    /**
     * admin login validation messages
     */
    public function messages()
    {
        return [
            'email.required' => __('message.email_required'),
            'email.regex' => __('message.email_regex'),
            'password.required' => __('message.password_required'),
        ];
    }    
}
