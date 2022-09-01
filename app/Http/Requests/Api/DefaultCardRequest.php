<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class DefaultCardRequest extends ApiRequest
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
            'id' => 'required',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [ 
            'id.required' => __('message.card_id_required'),
        ];
    } 
}
