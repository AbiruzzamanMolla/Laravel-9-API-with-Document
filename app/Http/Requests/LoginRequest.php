<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:100',
            'password' => 'required|min:6',
        ];
    }
}