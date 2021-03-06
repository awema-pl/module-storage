<?php

namespace AwemaPL\Storage\User\Sections\Categories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategory extends FormRequest
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
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|not_in:'.$this->id,
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
            'warehouse_id' => _p('storage::requests.user.category.attributes.warehouse_id', 'warehouse'),
            'name' => _p('storage::requests.user.category.attributes.name', 'name'),
            'parent_id' => _p('storage::requests.user.category.attributes.parent_id', 'parent category'),
            'external_id' => _p('storage::requests.user.category.attributes.external_id', 'external ID'),
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
            'duplicate_product.not_in' => _p('storage::requests.user.duplicate_product.messages.duplicate_product_and_product_not_same', 'Duplicate product and product cannot be the same.'),
        ];
    }
}
