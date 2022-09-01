<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class EditProfileRequest extends FormRequest
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
            'profile_name' => 'required|max:40',
            'birth_date' => 'required',
            'death_date' => 'required',
            'short_description' => 'required|max:70',
            'journey' => 'required|string|max:2000',
            'storiesArticleImage' => 'required_if:stories-articles_image-validation,==,required',
            'storiesArticleImage.*' => 'required_if:stories-articles_image-validation,==,required',
            'storiesArticleTitle' => 'required_if:stories-articles-validation,==,required|max:60',
            'storiesArticleTitle.*' => 'required_if:stories-articles-validation,==,required|max:60',
            'storiesArticleText' => 'required_if:stories-articles-validation,==,required|max:3600',
            'storiesArticleText.*' => 'required_if:stories-articles-validation,==,required|max:3600',
            'country' => 'required_with_all:address',
            'state' => 'required_with_all:address',
            'city' => 'required_with_all:address',
            'zip_code' => 'nullable|required_with_all:address',
            'note' => 'nullable|max:150',
            'image_caption' => 'nullable|max:70',
            'image_caption.*' => 'nullable|max:70',
            'video_caption' => 'nullable|max:70',
            'video_caption.*' => 'nullable|max:70',
            'audio_caption' => 'nullable|max:70',
            'audio_caption.*' => 'nullable|max:70',
            'gender' => 'required',
        ];
      
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        
        $messages = [];

        $messages['profile_name.required'] =  __('message.profile_name_required');
        $messages['profile_name.max'] =  __('message.profile_name_max');
        $messages['birth_date.required'] =  __('message.birth_date_required');
        $messages['death_date.required'] = __('message.death_date_required');
        $messages['short_description.required'] =  __('message.short_description_required');
        $messages['short_description.max'] =  __('message.short_description_max');
        $messages['short_description.regex'] =  __('message.short_description_regax');
        $messages['journey.required'] = __('message.journey_required');
        $messages['journey.max'] =  __('message.journey_max');
        $messages['storiesArticleImage.required_if'] = __('message.stories_image_required');
        $messages['storiesArticleTitle.required_if'] = __('message.stories_title_required');
        $messages['storiesArticleTitle.max'] = __('message.stories_title_max');
        $messages['storiesArticleTitle.regex'] = __('message.stories_title_regax');
        $messages['storiesArticleText.max'] = __('message.stories_tax_required');
        $messages['country.required_with_all'] = __('message.country_required');
        $messages['state.required_with_all'] = __('message.state_required');
        $messages['city.required_with_all'] = __('message.city_required');
        $messages['zip_code.required_with_all'] = __('message.zip_code_required');
        $messages['zip_code.alpha_num'] = __('message.zip_code_regx');
        $messages['note.required_with_all'] = __('message.note_required');
        $messages['note.max'] = __('message.note_max');
        $messages['image_caption.max'] = __('message.image_caption_max');
        $messages['video_caption.max'] = __('message.video_caption_max');
        $messages['audio_caption.max'] = __('message.voice_note_max');
       
        /* Checked validation in array */ 
        if (isset($this->request) && !empty($this->request->get('storiesArticleText'))) {
            $storiesArticleText = $this->request->get('storiesArticleText');
             foreach($storiesArticleText as $key =>$val) {
                $messages["storiesArticleText.{$key}.required_if"] = __('message.article_required');
                $messages["storiesArticleText.{$key}.max"] = __('message.article_max');
             }
         }
        return $messages;
    }    
}
