<?php

namespace AwemaPL\Storage\User\Sections\Sources\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductSource extends FormRequest
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
            'stock' => 'required|boolean',
            'availability' => 'required|boolean',
            'brutto_price' => 'required|boolean',
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'stock' => _p('storage::requests.user.source.attributes.stock', 'stock'),
            'availability' => _p('storage::requests.user.source.attributes.availability', 'availability'),
            'brutto_price' => _p('storage::requests.user.source.attributes.brutto_price', 'brutto price'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
