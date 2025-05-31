<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Retrieve all input data and safely reformat start_time and end_time.
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);

        if (!empty($data['start_time'])) {
            $data['start_time'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['start_time'])->format('Y-m-d H:i:s');
        }

        if (!empty($data['end_time'])) {
            $data['end_time'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['end_time'])->format('Y-m-d H:i:s');
        }
        
        $data['min_participants'] =1;
        $data['max_participants'] =200;

        return $data;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'appointment_name' => 'required|string|max:255',
            'class_id'         => 'required|exists:classes,id',
            'coach_id'         => 'required|exists:coaches,id',
            'min_participants' => 'required|integer|min:1',
            'max_participants' => 'required|integer|gte:min_participants',
            'start_time' => ['required', 'date', 'after:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'location'         => 'required|string|max:255',
        ];
    }

    /**
     * Custom validation for start_time < end_time.
     */
 

    /**
     * Customize error messages.
     */
    public function messages()
    {
        return [
            'max_participants.gte' => 'The maximum participants must be greater than or equal to the minimum participants.',
            'start_time.after'     => 'The start time must be a future time.',
            'end_time.after'       => 'The end time must be after the start time.',
        ];
    }
}
