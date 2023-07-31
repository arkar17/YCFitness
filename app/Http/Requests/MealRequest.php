<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
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
            'name' => 'required',
            'calories' => 'required',
            // 'meal_plan_id' => 'required',
            'carbohydrates' => 'required',
            'protein' => 'required',
            'fat' => 'required',
        ];
    }
}
