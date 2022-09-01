<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class SubscriptionRequest extends ApiRequest
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
            
            'terms_condition' => 'required',
            'subscription_id' => 'required',
            'email' => 'required_if:card_type,addNewCard|nullable|regex:'. config('constants.Regex.EMAIL'),
            'card_type' => 'required',
            'card_token' => 'required',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'email.required_if' => __('message.your_email_required'),
            'email.regex' => __('message.email_regex'),  
            'subscription_id.required' => __('message.subscription_required'),
            'terms_condition.required' => __('message.subscription_terms_required'),
            'card_token.required' => __('message.card_token_required'),
        ];
    } 
}
