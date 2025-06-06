<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
use App\Models\Appointment;
use App\Rules\AppointmentValidation;
use App\Rules\LookupRule;
use App\Rules\ValidSeatForAppointment;
use Symfony\Component\HttpFoundation\Response;


class AppointmentRequest extends FormRequest
{

    public function all($keys = null)
    {
        $data = parent::all($keys);

        $parameters = Request()->route()->parameters();
        $route = $this->route()->getName();

        if ($route == "appointment.show")
            $data['appointment_id'] =  $parameters['appointment_id'] ?? null;
        elseif ($route == "appointments.is_reserve")
            $data['appointment_id'] =  $parameters['appointment_id'] ?? null;
        elseif ($route == "appointment.seat.point")
            $data['appointment_id'] =  $parameters['appointment_id'] ?? null;
        elseif ($route == "appointment.coach")
            $data['coach_id'] =  $parameters['coach_id'] ?? null;

        return  $data;
    }

    /** appointment
     * 
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

        $route = $this->route()->getName();
        $user =  auth()->user();


        if ($route == "appointment.index")
            return [
                "date" => ['required', 'date_format:Y-m-d'],
                "filter_id" => ['nullable',new LookupRule()]
            ];
        if ($route == "appointment.coach")
            return [
                "coach_id" => ['required', 'exists:coaches,id'],
                "date" => ['required', 'date_format:Y-m-d'],
            ];
        elseif ($route == "appointment.show")
            return [
                "appointment_id" => ['required', 'exists:appointments,id'],
            ];
        elseif ($route == "appointments.is_reserve")
            return [
                "appointment_id" => ['required', 'exists:appointments,id'],
            ];
        elseif ($route == "appointment.seat.point")
            return [
                "appointment_id" => ['required', 'exists:appointments,id'],
            ];
        elseif ($route == "appointment.reserve") {
            $appointment = Appointment::find($this->appointment_id);

            $rules = [
                "appointment_id" => [
                    'required',
                    'exists:appointments,id',
                    new AppointmentValidation($this->user()),
                    new ValidSeatForAppointment($this->seat_point_id)
                ],

                "seat_point_id" => [
                    'nullable',
                    'exists:seat_points,id'
                ],

                "guest_name"=>['nullable','string'],
                
            ];
 
            if ($appointment && $appointment->class->seat_selection_required) {
                $rules['seat_point_id'] = [
                    'required',
                    'exists:seat_points,id'
                ];
            }
        }

        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(

            ApiResponse::error("Unprocessable entity", Response::HTTP_UNPROCESSABLE_ENTITY, $errors)

        );
    }
}
