<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            'name'              => 'required',
            'price'             => 'required|integer',
            'interval'          => 'required',
            'currency'          => 'required',
        ];
    }

    public function messages()
    {
        return[
            'name.required'              => 'Name is required',
            'price.required'             => 'price is required',
            'interval.required'          => 'Interval is required',
            'currency.required'          => 'Currency required',
        ];
    }
}
