<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDuplicateProduct extends FormRequest
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
            'duplicate_product_id' => 'required|integer|not_in:'.$this->product_id,
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
            'warehouse_id' => _p('storage::requests.user.duplicate_product.attributes.warehouse_id', 'warehouse'),
            'duplicate_product_id' => _p('storage::requests.user.duplicate_product.attributes.duplicate_product_id', 'duplicate product'),
            'product_id' => _p('storage::requests.user.duplicate_product.attributes.product_id', 'product'),
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
            'duplicate_product_id.not_in' => _p('storage::requests.user.category.messages.current_category_not_be_parent_category', 'The current category cannot be a parent category.'),
        ];
    }
}
