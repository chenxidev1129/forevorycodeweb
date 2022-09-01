<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCardRequest extends FormRequest
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
            'card_holder' => 'required|max:50|regex:'.config('constants.Regex.NAME'),
            'card_number' => 'required',
            'exp_date' => 'required|min:5|max:5|card_exp_date',
            'card_cvv' => 'required|digits_between:3,4',
            'address' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',   
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'card_holder.required' => __('message.card_holder_required'),
            'card_holder.max' => __('message.card_holder_max'),
            'card_holder.regex' => __('message.name_regex'),
            'card_number.required' => __('message.card_number_required'),
            'exp_date.required' => __('message.exp_date_required'),
            'exp_date.min' => __('message.valid_exp_date'),
            'exp_date.max' => __('message.valid_exp_date'),
            'exp_date.card_exp_date' => __('message.valid_exp_date'),
            'card_cvv.required' => __('message.card_cvv_required'),
            'card_cvv.min' => __('message.valid_cvv'),
            'card_cvv.max' => __('message.valid_cvv'),
            'address.required' => __('message.address_required'),           
            'zip_code.required' => __('message.zip_code_required'),
            'zip_code.min' => __('message.zip_code_regx'),
            'zip_code.max' => __('message.zip_code_regx'),
            'zip_code.alpha_num' => __('message.zip_code_regx'),  
            'country.required' => __('message.country_required'),
            'state.required' => __('message.state_required'),
            'city.required' => __('message.city_required'), 
        ];
    }  
}
