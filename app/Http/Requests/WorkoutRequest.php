<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkoutRequest extends FormRequest
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
            'plantype' => 'required',
            'workoutname' => 'required',
            'memberType' => 'required',
            'calories' => 'required',
            'workoutday' => 'required',
            'workoutplace' => 'required',
            'workoutlevel' => 'required',
            'gendertype' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg,gif',
            'video' => 'required|mimes:mp4,mov,webm',
            'estimateTime' => 'required',
            'sets' => 'required'
        ];
    }
}
