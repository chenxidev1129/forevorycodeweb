<?php

namespace App\Http\Requests\Api;
use App\Http\Requests\Api\ApiRequest;

class SignUpRequest extends ApiRequest
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
   
        $rules = [];
        $rules['first_name'] = 'required|min:1|max:20|regex:'.config('constants.Regex.NAME');
        $rules['last_name'] = 'required|min:1|max:20|regex:'.config('constants.Regex.NAME');
        $rules['email'] = 'required|check_email|regex:'. config('constants.Regex.EMAIL');
        $rules['phone_number'] = 'required|check_phone_format|check_phone';
        $rules['address'] = 'required';
        $rules['zip_code'] = 'required';
        $rules['country'] =  'required';
        $rules['country_code'] =  'required';
        $rules['country_short_name'] =  'required';
        $rules['country_iso_code'] =  'required';
        $rules['state'] = 'required';
        $rules['city'] =  'required';
        $rules['lat'] =  'required';
        $rules['lng'] =  'required';
        $rules['password'] = 'required|min:6|max:20|regex:'.config('constants.Regex.PASSWORD');
        
        return $rules;
    }

    /**
     * User sign up validation messages.
     */
    public function messages()
    {
        return [
            'first_name.required' => __('message.first_name_required'),
            'first_name.max' => __('message.first_name_max'),
            'first_name.regex' => __('message.first_name_regex'),
            'last_name.required' => __('message.last_name_required'),
            'last_name.max' => __('message.last_name_max'),
            'last_name.regex' => __('message.last_name_regex'),
            'email.required' => __('message.your_email_required'),
            'email.regex' => __('message.email_regex'),
            'email.check_email' => __('message.email_exist'),
            'phone_number.check_phone_format' => __('message.phone_regex'),
            'phone_number.required' => __('message.phone_number_required'),
            'phone_number.check_phone' => __('message.phone_number_exist'),
            'address.required' => __('message.address_required'),
            //'address.max' =>  __('message.address_max'),
            'zip_code.required' => __('message.zip_code_required'),
            'zip_code.min' => __('message.zip_code_regx'),
            'zip_code.alpha_num' => __('message.zip_code_regx'),
            'country.required' => __('message.country_required'),
            'state.required' => __('message.state_required'),
            'city.required' => __('message.city_required'),
            'password.required' => __('message.password_required'),
            'password.min' => __('message.password_min'),
            'password.max' => __('message.password_max'),
            'password.regex' => __('message.password_regex'),
            'country_code.required' => __('message.country_code_required'),
            'country_short_name.required' => __('message.country_short_name'),
            'lat.required' => __('message.lat_required'),
            'lng.required' => __('message.lang_required'),
        ];
    } 
}
