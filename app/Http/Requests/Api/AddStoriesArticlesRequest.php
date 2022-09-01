<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class AddStoriesArticlesRequest extends ApiRequest
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
            'title' => 'required|max:60',
            'text' => 'required|max:3600',
            'image' => 'required|image',
        ]; 
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'title.required' => __('message.stories_title_required'),
            'title.max' => __('message.stories_title_max'),
            'text.required' => __('message.article_required'),
            'text.max' =>  __('message.article_max'),
            'image.image' => __('message.valid_stories_article_image_required')
        ];
    }  
}
