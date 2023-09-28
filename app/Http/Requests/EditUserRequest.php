<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditUserRequest extends FormRequest
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
        
        // $rule['first_name']    = 'sometimes|required';
        // $rule['last_name']     = 'sometimes|required';
        $rule['email']         = 'sometimes|required|email|unique:users,email,'.$request->id.',id,deleted_at,NULL';
        $rule['user_name']     = 'sometimes|required';
        $rule['phone_number']  = 'sometimes|required|min:10';
        // $rule['profile_image'] = 'sometimes|required|image|mimes:jpeg,png,jpg';
        $rule['address']       = 'sometimes|required';
        // $rule['address_2']     = 'sometimes|required';
        $rule['city']     = 'sometimes|required';
        $rule['state']         = 'sometimes|required';
        $rule['zipcode']       = 'sometimes|required';
        return $rule;
    }

    public function messages()
    {
        return[
            // 'first_name.required'    => 'First Name is required',
            // 'last_name.required'     => 'Last Name is required',
            'user_name.required'     => 'Username is required',
            'email.required'         => 'Email is required',
            'phone_number.required'  => 'Phone number is required',
            'phone_number.min'       => 'Enter minimum 10 digits',
            // 'profile_image.required' => 'Profile is required',
            // 'profile_image.image'    => 'Enter only image',
            // 'profile_image.mimes'    => 'Enter only image jpeg,png and jpg.',
            'address.required'       => 'Address is required.',
            // 'address_2.required'     => 'Address_2 is required.',
            'city.required'          => 'City is required.',
            'state.required'         => 'State is required.',
            'zipcode.required'       => 'Zipcode is required.',
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
