<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class ChangePasswordRequest extends ApiRequest
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
            'confirm_password' => 'required|same:new_password',
            'new_password' => 'required|min:6|max:20|regex:'. config('constants.Regex.PASSWORD'),
            'current_password' => 'required',
        ]; 
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'current_password.required' => __('message.current_password_required'),
            'new_password.required' => __('message.new_password_required'),
            'new_password.min' => __('message.password_min'),
            'new_password.max' => __('message.password_max'),
            'new_password.regex' => __('message.password_regex'),
            'confirm_password.required' => __('message.password_confirmation_required'),
            'confirm_password.same' => __('message.update_password_confirm'),
            
        ];
    }
}
