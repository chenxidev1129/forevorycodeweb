<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPlan extends FormRequest
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

        $rules['price'] =  "required|regex:/^\d+(\.\d{1,2})?$/";
        $rules['slug'] = "required";
        $rules['days'] = "required_if:slug,free_trial";
        
       return $rules;

    }

    
    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'price.required' => __('message.subscription_price_required'),
            'price.regex' => __('message.subscription_price_regex'),     
            'days.required_if' => __('message.days_required'),
            'days.min' => __('message.days_min'),
            'days.integer' => __('message.days_valid'),
        ];
       
    }
}
