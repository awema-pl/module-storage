<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouse extends FormRequest
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
            'name' => 'required|string|max:255',
            'duplicate_product_settings.external_id' =>'required|boolean',
            'duplicate_product_settings.gtin' => 'required|boolean',
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
            'name' => _p('storage::requests.user.warehouse.attributes.name', 'name'),
            'duplicate_products.external_id' => _p('storage::requests.user.warehouse.attributes.duplicate_product_settings.external_id', 'generate duplicate products via external ID'),
            'duplicate_products.gtin' => _p('storage::requests.user.warehouse.attributes.duplicate_product_settings.gtin', 'generate duplicate products via GTIN'),
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
