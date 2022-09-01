<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessAccountRequest extends FormRequest
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
            'first_name' => 'required|min:1|max:40|regex:/^[a-zA-Z\s]*$/',
            'user_type' => 'required',
            'email' => 'required|check_email|regex:' . config('constants.Regex.EMAIL')
        ]; 
       
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return  [
            'first_name.required' => __('message.name_required'),
            'first_name.max' => __('message.name_limit'),
            'first_name.regex' => __('message.name_regex'),
            'user_type.required' => __('message.role_required'),
            'email.required' => __('message.enter_email_required'),
            'email.check_email' => __('message.email_exist'),
            'email.regex' => __('message.email_regex')
        ];
        
    }

}
