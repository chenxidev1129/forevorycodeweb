<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;

class UploadMediaVoiceNoteRequest extends ApiRequest
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
            'caption' => 'nullable|max:70',
        ];
    }

    public function message(){
        return [
            'caption.required' => __('message.voice_note_caption_required'),
        ];
    }
}
