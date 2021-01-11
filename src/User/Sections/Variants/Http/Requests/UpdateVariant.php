<?php

namespace AwemaPL\Storage\User\Sections\Variants\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVariant extends FormRequest
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
            'active' => 'required|boolean',
            'name' => 'required|string|max:255',
            'gtin' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:255',
            'stock' => 'required|integer',
            'availability' => 'nullable|string|max:255',
            'brutto_price' => 'required|integer|between:0,99999999.9999',
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
            'warehouse_id' => _p('storage::requests.user.variant.attributes.warehouse_id', 'warehouse'),
            'product_id' =>  _p('storage::requests.user.variant.attributes.product_id', 'product'),
            'active' =>  _p('storage::requests.user.variant.attributes.active', 'active'),
            'name' =>  _p('storage::requests.user.variant.attributes.name', 'name'),
            'gtin' => _p('storage::requests.user.variant.attributes.gtin', 'GTIN'),
            'sku' =>  _p('storage::requests.user.variant.attributes.sku', 'SKU'),
            'stock' => _p('storage::requests.user.variant.attributes.stock', 'stock'),
            'availability' => _p('storage::requests.user.variant.attributes.availability', 'availability'),
            'brutto_price' =>  _p('storage::requests.user.variant.attributes.brutto_price', 'brutto price'),
            'external_id' =>  _p('storage::requests.user.variant.attributes.external_id', 'external ID'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.between' => _p('storage::requests.user.variant.messages.number_outside_range', 'The given number is outside the allowed range.'),
        ];
    }
}
