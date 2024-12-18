<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ClassModel; // Assuming the model name is ClassModel

class UpdateClassRequest extends FormRequest
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
            'name'                    => 'required|string|max:255',
            'room'                    => 'nullable|string|max:255',
            'capacity'                => 'required|integer|min:1',
            'type_name'               => 'required|string|in:sint,odio,occaecati,consequuntur',
            'booking_process'         => 'required|string|in:In-Person,Online',
            'description'             => 'nullable|string',
            'photo'                   => 'nullable|image|max:2048',
 
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
            'name.required' => 'Class name is required.',
            'capacity.required' => 'Capacity is required.',
            // Add more custom messages as needed
        ];
    }
}
