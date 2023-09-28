<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EditAdministratorRequest extends FormRequest
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
            'role_id'           => 'required',
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email|unique:administrators,email,'.$request->id.',id,deleted_at,NULL',
            'phone_number'      => 'required|min:10',
            // 'password'          => 'required',
            // 'confirm_password'  => 'required|same:password',
        ];
    }

    public function messages()
    {
        return[
            'role_id.required'          => 'Please select role',
            'first_name.required'       => 'First Name is required',
            'last_name.required'        => 'Last Name is required',
            'email.required'            => 'Email is required',
            'email.email'               => 'Entar valid email',
            'email.unique'              => 'Email is already exit',
            'phone_number.required'     => 'Phone number is required',
            'phone_number.min'          => 'Enter minimum 10 digits',
            // 'password.required'         => 'Password is required',
            // 'confirm_password.required' => 'Confirm password is required.',
            // 'confirm_password.same'     => 'Password and confirm password does not match.'
        ];
    }
}
