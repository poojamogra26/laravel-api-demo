<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class companyEditRequest extends FormRequest
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
            'name'         => 'sometimes|required',
            'email'        => 'sometimes|required|email|unique:companies,email,'.$request->id.',user_id',
            'phone_number' => 'sometimes|required|min:10|max:10',
            'address'      => 'sometimes|required',
            'address_2'    => 'sometimes|required',
            'city'         => 'sometimes|required',
            'state'        => 'sometimes|required',
            'zipcode'      => 'sometimes|required',
        ];
    }

    public function messages()
    {
        return[
            'name.required'         => 'Name is required',
            'email.required'        => 'email is required',
            'phone_number.required' => 'Phone number is required',
            'address.required'      => 'Address is required',
            'address_2.required'    => 'Address_2 is required',
            'city.required'         => 'City is required',
            'state.required'        => 'State is required',
            'zipcode.required'      => 'Zipcode is required',
        ];
    }
}
