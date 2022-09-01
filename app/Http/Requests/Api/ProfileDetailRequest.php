<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class ProfileDetailRequest extends ApiRequest
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
            'short_description' => 'required|max:70',
            'date_of_birth' => 'required',
            'date_of_death' => 'required',
            'profile_name' => 'required|max:40',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'profile_name.required' =>  'Please enter the name of the loved one',
            'profile_name.max' => 'Name cannot exceed 40 characters',
            'birth_date.required' =>  'Please provide the Date of birth',
            'death_date.required' => 'Please provide the Date of death',
            'short_description.required' => 'Please enter the short description',
            'short_description.max' => 'Short description cannot exceed 70 characters',
           // 'short_description.regex' => 'Alphanumeric 0-9, the letters A to Z both uppercase and lowercase are allowed',           
        ];
    } 
}
