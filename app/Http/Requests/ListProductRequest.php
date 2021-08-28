<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListProductRequest extends FormRequest
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
            "groupCode" => "nullable|",
            "priceFrom" => "nullable|regex:/^\d*(\.\d{2})?$/|lt:priceTo",
            "priceTo" => "nullable|regex:/^\d*(\.\d{2})?$/|gte:priceFrom",
            "search" => "nullable",
            "sort" => "nullable",
        ]; 
    }
}
