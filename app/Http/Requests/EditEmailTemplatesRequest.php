<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditEmailTemplatesRequest extends FormRequest
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
            'subject'       => 'sometimes|required',
            'description'   => 'sometimes|required',
            'template_text' => 'sometimes|required'
        ];
    }

    public function messages()
    {
        return [
            'subject.required'       => 'Subject is required',
            'description.required'   => 'Description is required',
            'template_text.required' => 'Enter text body',
        ];
    }
}
