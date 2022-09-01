<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class ForgotPasswordRequest extends ApiRequest
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
            'email' => 'required|max:255|regex:' . config('constants.Regex.EMAIL'),
        ];
    }

    /**
     * validation messages
     */
    public function messages()
    {
        return [
            'email.required' =>  __('message.register_emai_required'),
            'email.regex' => __('message.email_regex')
        ];
    }
}
