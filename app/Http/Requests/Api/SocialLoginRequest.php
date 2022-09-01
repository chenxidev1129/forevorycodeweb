<?php

namespace App\Http\Requests\Api;
use App\Http\Requests\Api\ApiRequest;

class SocialLoginRequest extends ApiRequest
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
            'login_type' => 'required',
            'auth_token' => 'required',
        ];
    }

      /**
     * User sign up validation messages.
     */
    public function messages()
    {
        return [
            'device_token.device_token' => __('message.device_token_required'),
            'login_type.required' => __('message.login_type_required'),
            'auth_token.required' => __('message.auth_token_required'),
        ];
    } 
}
