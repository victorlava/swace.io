<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCallback extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'coingate_id' => 'required|numeric'
            // Just making sure that no-one tries to fake POST with some random letters
            // No need to check anything else, because we have a hash check inside a method
        ];
    }
}
