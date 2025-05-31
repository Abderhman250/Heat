<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DeviceToken;

class NotificationRequest extends FormRequest
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

    protected function handleDataValidation() : void
    {
        $users = [];
        $datas = $this->device_tokens;
        foreach($datas as $data)
            $users[] = DeviceToken::where(['token' => $data])->pluck('user_id')->first();

        $this->replace(array_merge($this->all(), ["users" => $users]));
    }
    protected function passedValidation(): void
    {
        $route = $this->route()->getName();
        if ($route === "admin.notifications.push")
            $this->handleDataValidation();

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'   => ['required'],
            'body'    => ['required'],
            'device_tokens' => ['required' , 'array'],
        ];
    }
}
