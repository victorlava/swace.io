<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isVerified();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $minAmount = env('SWACE_MIN_BUY_AMOUNT');
        $maxAmount = env('SWACE_MAX_BUY_AMOUNT');

        return [
            'amount' => 'required|numeric',
            'tokens' => "required|gte:{$minAmount}|lte:{$maxAmount}",
            'currency' => 'required|numeric|exists:currencies,id',
        ];
    }
}
