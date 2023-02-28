<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class APISubmitOrderPostRequest extends FormRequest
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
            'customer_id'       => 'required|integer',
            'payment_method'    => 'required|string',
            'line_items'        => 'required',
            'set_paid'          => 'required',
            'billing'           => 'required_without:store_id',
            'shipping'          => 'required_without:store_id',
            'store_id'          => 'required_without:shipping|required_without:billing',
            'shipping_lines'    => 'required',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'message'   => Collection::make($errors)->first()[0] ?? "",
                'status'    => 'error',
                'errors'    => $errors
            ], 200)
        );
    }
}
