<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EditClientRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        return [
            //
            'name'              => 'sometimes|required',
            // 'email'             => 'sometimes|required|email|unique:clients,email,'.$request->id.',id',
            // 'phone'             => 'sometimes|required|min:10|max:10',
            'price'             => 'sometimes|required',
            // 'schedule_reminder' => 'sometimes|required',
            // 'schedule_date'     => 'sometimes|required',
            // 'description'       => 'sometimes|required',
            // 'address'           => 'sometimes|required',
            // 'city'              => 'sometimes|required',   
            // 'state'             => 'sometimes|required',
            // 'zip_code'          => 'sometimes|required',
            // 'is_schedule_reminder_sent' => 'sometimes|required',
            // 'status'                    => 'sometimes|required'
        ];
    }

    public function messages()
    {
        return[
            'name.required'              => 'Name is required',
            // 'email.required'             => 'Email is required',
            // 'phone.required'             => 'Phone Number is required',
            'price.required'             => 'Price is required',
            // 'schedule_reminder.required' => 'Entar valid Schedule Reminder',
            // 'schedule_date.required'     => 'Reminder Date is required',
            // 'description.required'       => 'Description is required',
            // 'address.required'           => 'Address is required',
            // 'city.required'              => 'City is required',
            // 'state.required'             => 'State is required',
            // 'zip_code.required'          => 'Zipcode is required',
            // 'is_schedule_reminder_sent.required' => 'is_schedule_reminder_sent is required',
            // 'status.required'                    => 'Status is required',
        ];
    }
}
