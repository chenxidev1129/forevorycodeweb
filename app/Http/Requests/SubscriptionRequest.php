<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            'card_holder' => 'required_if:card_type,addNewCard|nullable|max:50|regex:'.config('constants.Regex.NAME'),
            'email' => 'required_if:card_type,addNewCard|nullable|regex:'. config('constants.Regex.EMAIL'),
            'card_number' => 'required_if:card_type,addNewCard|nullable',
            'exp_date' => 'required_if:card_type,addNewCard|nullable|min:5|max:5|card_exp_date',
            'card_cvv' => 'required_if:card_type,addNewCard|nullable|digits_between:3,4',
            'address' => 'required_if:card_type,addNewCard|nullable',
            'zip_code' => 'required_if:card_type,addNewCard|nullable',
            'country' => 'required_if:card_type,addNewCard',
            'state' => 'required_if:card_type,addNewCard',
            'city' => 'required_if:card_type,addNewCard',   
            'subscription_id' => 'required',
            'terms_condition' => 'required',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'card_holder.required_if' => __('message.card_holder_required'),
            'card_holder.max' => __('message.card_holder_max'),
            'card_holder.regex' => __('message.name_regex'),
            'email.required_if' => __('message.your_email_required'),
            'email.regex' => __('message.email_regex'),  
            'card_number.required_if' => __('message.card_number_required'),
            'exp_date.required_if' => __('message.exp_date_required'),
            'exp_date.min' => __('message.valid_exp_date'),
            'exp_date.max' => __('message.valid_exp_date'),
            'exp_date.card_exp_date' => __('message.valid_exp_date'),
            'card_cvv.required_if' => __('message.card_cvv_required'),
            'card_cvv.min' => __('message.valid_cvv'),
            'card_cvv.max' => __('message.valid_cvv'),
            'address.required_if' => __('message.address_required'),         
            'zip_code.required_if' => __('message.zip_code_required'),
            'zip_code.min' => __('message.zip_code_regx'),
            'zip_code.max' => __('message.zip_code_regx'),
            'zip_code.alpha_num' => __('message.zip_code_regx'),  
            'country.required_if' => __('message.country_required'),
            'state.required_if' => __('message.state_required'),
            'city.required_if' => __('message.city_required'), 
            'subscription_id.required' => __('message.subscription_required'),
            'terms_condition.required' => __('message.subscription_terms_required')
        ];
    }    
}
