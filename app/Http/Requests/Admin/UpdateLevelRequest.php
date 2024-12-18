<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLevelRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users for now
    }

    public function rules()
    {
        $id = $this->route('id'); // Fetch the ID from the route parameter

        return [
            'title_levels' => 'required|string|max:255',
            'required_classes' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('levels', 'required_classes')->ignore($id), // Check uniqueness excluding current record
            ],
        ];
    }

    public function messages()
    {
        return [
            'title_levels.required' => 'The Title Levels field is required.',
            'required_classes.required' => 'The Required Classes field is required.',
            'required_classes.unique' => 'The Required Classes value already exists.',
            'required_classes.min' => 'The Required Classes value must be at least 1.',
        ];
    }
}
