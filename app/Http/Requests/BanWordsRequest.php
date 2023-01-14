<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BanWordsRequest extends FormRequest
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
            'ban_word_english' => 'required',
            'ban_word_myanmar' => 'required',
            'ban_word_myanglish' => 'required',
        ];
    }
}
