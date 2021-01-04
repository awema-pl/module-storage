<?php

namespace AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryProduct extends FormRequest
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
            'category_id' => 'required|integer',
            'product_id' =>  ['required', 'integer', Rule::unique(config('storage.database.tables.storage_category_product'))->where(function ($query) {
                return $query->where('category_id', $this->category_id);
            })],
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
            'warehouse_id' => _p('storage::requests.user.category_product.attributes.warehouse_id', 'warehouse'),
            'category_id' =>  _p('storage::requests.user.category_product.attributes.category_id', 'category'),
            'product_id' =>  _p('storage::requests.user.category_product.attributes.product_id', 'product'),
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
            'product_id.unique' => _p('storage::requests.user.category_product.messages.category_product_already_exist', 'This product is already in the category.'),
        ];
    }
}
