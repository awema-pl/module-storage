<?php

namespace AwemaPL\Storage\User\Sections\Images\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImage extends FormRequest
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
            'warehouse_id' => 'required|integer',
            'product_id' => 'required|integer',
            'variant_id' => 'nullable|integer',
            'url' => 'required|string|max:65535',
            'external_id' => 'nullable|string|max:255',
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
            'warehouse_id' => _p('storage::requests.user.image.attributes.warehouse_id', 'warehouse'),
            'product_id' =>  _p('storage::requests.user.image.attributes.product_id', 'product'),
            'variant_id' =>  _p('storage::requests.user.image.attributes.variant_id', 'variant'),
            'url' =>  _p('storage::requests.user.image.attributes.url', 'Web address'),
            'external_id' =>  _p('storage::requests.user.image.attributes.external_id', 'external ID'),
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
