<?php

namespace App\Http\Requests;

class StoreProductRequest extends ApiFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:1024',
        ];
    }
}
