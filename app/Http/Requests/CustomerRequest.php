<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'phone' => 'required|min:9|max:11|unique:users',
            'email' => 'required|unique:users',
            'address' => 'required',
            'password' => 'required|min:6|max:11',
            'password_confirmation' => 'required|same:password'
        ];
    }
}
