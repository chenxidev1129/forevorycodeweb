<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class AddGraveSiteLocationRequest extends ApiRequest
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
                'lang' => 'required',
                'lat' => 'required',
                'note' => 'nullable|max:150',
                'zip_code' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'address' => 'required',
        ]; 
    }
    
    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
                'address.required' => __('message.address_required'),
                'country.required' => __('message.country_required'),
                'state.required' => __('message.state_required'),
                'city.required' => __('message.city_required'),
                'zip_code.required' => __('message.zip_code_required'),
                'note.max' => __('message.note_max'),
                'lat.required' => __('message.lat_required'),
                'lang.required' => __('message.lang_required'),
           
        ];
    }

}
