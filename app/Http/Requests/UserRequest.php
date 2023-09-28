<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'first_name'        => 'required',
            'last_name'         => 'required',
            'user_name'         => 'required',
            'email'             => 'required|email|unique:users',
            'phone_number'      => 'required|min:10',
            'password'          => 'required',
            'confirm_password'  => 'required|same:password',
            'profile_image'     => 'required|image|mimes:jpeg,png,jpg',
        ];
    }

    public function messages()
    {
        return[
            'first_name.required'       => 'First Name is required',
            'last_name.required'        => 'Last Name is required',
            'user_name.required'        => 'Username is required',
            'email.required'            => 'Email is required',
            'email.email'               => 'Entar valid email',
            'email.unique'              => 'Email is already exit',
            'phone_number.required'     => 'Phone number is required',
            'phone_number.min'          => 'Enter minimum 10 digits',
            'password.required'         => 'Password is required',
            'confirm_password.required' => 'Confirm password is required.',
            'confirm_password.same'     => 'Password and confirm password does not match.',
            'profile_image.required'    => 'Profile is required',
            'profile_image.image'       => 'Enter only image',
            'profile_image.mimes'       => 'Enter only image jpeg,png and jpg.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]));
    }

}
