<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
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
 
        if ($route == "appointment.index")
            return [
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
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(

            ApiResponse::error("Unprocessable entity", Response::HTTP_UNPROCESSABLE_ENTITY, $errors)

        );
    }
}
