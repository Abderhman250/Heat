<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCoachRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users
    }

    public function rules()
    {
        $id = $this->route('id'); // Fetch the ID from route

        return [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'country_code'  => 'required|string|max:5',
            'gender'        => 'required|boolean',
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user_id, 'id')],
            'dob'           => 'nullable|date',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty'     => 'required|string|max:255',
            'bio'           => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'email.unique' => 'This email is already in use.',
            'specialty.required' => 'The specialty is required.',
        ];
    }
}
