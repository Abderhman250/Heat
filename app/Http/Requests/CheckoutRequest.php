<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ApiResponse;
use App\Models\Plan;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        // If you want to restrict access, you can add authorization logic here.
        return true;
    }

    public function rules()
    {
        return [
            'plan_id' => 'required|exists:plans,id', // Ensure the booking exists
            'transaction_status' => 'required|string|in:pending,completed,failed,cancelled', // Define valid statuses
            'payment_method' => 'required|string|in:credit_card,debit_card,bank_transfer,cash', // Define valid payment methods
            'amount' => 'required|numeric|min:0', // Amount should be a valid number and cannot be negative
            'is_successful' => 'required|boolean', // The transaction should be true or false
            'transaction_time' => 'required|date_format:Y-m-d H:i:s', // Ensure the time is a valid date
        ];
    }

    public function messages()
    {
        return [
            'plan_id.required' => 'Plan ID is required.',
            'transaction_status.required' => 'Transaction status is required.',
            'payment_method.required' => 'Payment method is required.',
            'amount.required' => 'Amount is required.',
            'is_successful.required' => 'Transaction success status is required.',
            'transaction_time.required' => 'Transaction time is required.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // Get the plan ID and amount from the request
            $planId = $this->input('plan_id');
            $amount = $this->input('amount');
            
            // Check if the plan exists and if the amount matches the plan price
            $plan = Plan::find($planId);
            
            if ($plan && $plan->price != $amount) {
                // Add a custom validation error if the amount doesn't match the plan price
                $validator->errors()->add('amount', 'The amount must match the price of the selected plan.');
            }
        });
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(

            ApiResponse::error("Unprocessable entity", Response::HTTP_UNPROCESSABLE_ENTITY, $errors)

        );
    }
}
