<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiFormRequest extends FormRequest
{
    use ResponseTrait;

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
     * @param Validator $validator
     *
     * @return array
     */
    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(
            $this->responseError(
                null,
                'Something went wrong.',
                $validator->errors(),
                403
            )
        );
    }
}
