<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        if (!empty($data['start_time'])) {
            $data['start_time'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['start_time'])->format('Y-m-d H:i:s');
        }

        if (!empty($data['end_time'])) {
            $data['end_time'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['end_time'])->format('Y-m-d H:i:s');
        }
 
        return $data;
    }

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

    public function messages()
    {
        return [
            'max_participants.gte' => 'Maximum participants must be greater than or equal to the minimum participants.',
            'start_time.date'      => 'The start time must be a valid date.',
            'end_time.after'       => 'The end time must be after the start time.',
        ];
    }
}
