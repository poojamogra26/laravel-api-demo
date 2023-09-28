<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
    public function rules()
    {
        return [
            //
            'name'              => 'required',
            // 'email'             => 'required|email|unique:clients',
            // 'phone'             => 'required|min:10|max:10',
            'price'             => 'required',
            // 'schedule_reminder' => 'required',
            // 'schedule_date'     => 'required',
            // 'description'       => 'required',
            // 'address'           => 'required',
            // 'city'              => 'required',   
            // 'state'             => 'required',
            // 'zip_code'          => 'required',
            // 'is_schedule_reminder_sent' => 'required',
            // 'status'                    => 'required'
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
