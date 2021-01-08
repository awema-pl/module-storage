<?php

namespace AwemaPL\Storage\User\Sections\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
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
            'default_category_id' => 'required|integer',
            'manufacturer_id' => 'nullable|integer',
            'active' => 'required|boolean',
            'name' => 'required|string|max:255',
            'ean' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:255',
            'stock' => 'required|integer',
            'availability' => 'nullable|string|max:255',
            'brutto_price' => 'required|integer|between:0,99999999.9999',
            'tax_rate' => 'required|integer|between:0,100',
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
            'warehouse_id' => _p('storage::requests.user.product.attributes.warehouse_id', 'warehouse'),
            'default_category_id' =>  _p('storage::requests.user.product.attributes.default_category_id', 'default category'),
            'manufacturer_id' =>  _p('storage::requests.user.product.attributes.manufacturer_id', 'manufacturer'),
            'active' =>  _p('storage::requests.user.product.attributes.active', 'active'),
            'name' =>  _p('storage::requests.user.product.attributes.name', 'name'),
            'ean' => _p('storage::requests.user.product.attributes.ean', 'EAN'),
            'sku' =>  _p('storage::requests.user.product.attributes.sku', 'SKU'),
            'stock' => _p('storage::requests.user.product.attributes.stock', 'stock'),
            'availability' => _p('storage::requests.user.product.attributes.availability', 'availability'),
            'brutto_price' =>  _p('storage::requests.user.product.attributes.brutto_price', 'brutto price'),
            'tax_rate' =>  _p('storage::requests.user.product.attributes.tax_rate', 'tax rate'),
            'external_id' =>  _p('storage::requests.user.product.attributes.external_id', 'external ID'),
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
            'name.between' => _p('storage::requests.user.product.messages.number_outside_range', 'The given number is outside the allowed range.'),
        ];
    }
}
