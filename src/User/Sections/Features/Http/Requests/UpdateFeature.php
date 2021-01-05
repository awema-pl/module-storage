<?php

namespace AwemaPL\Storage\User\Sections\Features\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeature extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique(config('storage.database.tables.storage_features'))->where(function ($query) {
                return $query->where('product_id', $this->product_id)
                    ->where('variant_id', $this->variant_id);
            })->ignore($this->id)],
            'value' => 'required|string|max:65535',
            'type' => 'nullable|string|max:255',
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
            'warehouse_id' => _p('storage::requests.user.feature.attributes.warehouse_id', 'warehouse'),
            'product_id' =>  _p('storage::requests.user.feature.attributes.product_id', 'product'),
            'variant_id' =>  _p('storage::requests.user.feature.attributes.variant_id', 'variant'),
            'name' =>  _p('storage::requests.user.feature.attributes.name', 'name'),
            'value' =>  _p('storage::requests.user.feature.attributes.value', 'feature'),
            'type' =>  _p('storage::requests.user.feature.attributes.type', 'type'),
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
            'name.unique' => _p('storage::requests.user.feature.messages.feature_already_exist', 'This feature type already exists.'),
        ];
    }
}
