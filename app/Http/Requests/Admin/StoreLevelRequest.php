<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLevelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Allow access for all users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_levels' => 'required|string|max:255',
            'required_classes' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    // Check if the number already exists in the 'levels' table
                    if (\App\Models\Level::where('required_classes', $value)->exists()) {
                        $fail('The required classes number already exists.');
                    }
                },
            ],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title_levels.required' => 'The title levels field is required.',
            'required_classes.required' => 'The required classes field is required.',
            'required_classes.integer' => 'The required classes must be an integer.',
        ];
    }
}
