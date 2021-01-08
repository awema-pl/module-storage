<?php

namespace AwemaPL\Storage\User\Sections\Sources\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSource extends FormRequest
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
            'sourceable_type' => 'required|string|max:255',
            'sourceable_id' => ['required', 'integer', Rule::unique(config('storage.database.tables.storage_sources'))->where(function ($query) {
                return $query->where('warehouse_id', $this->warehouse_id)
                    ->where('sourceable_type', $this->sourceable_type);
            })],
            'settings.manufacturer_attribute_name' =>'nullable|string|max:255',
            'settings.default_tax_rate' => 'nullable|integer|between:0,100',
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
            'warehouse_id' => _p('storage::requests.user.source.attributes.warehouse_id', 'warehouse'),
            'sourceable_type' => _p('storage::requests.user.source.attributes.sourceable_type', 'source type'),
            'sourceable_id' => _p('storage::requests.user.source.attributes.sourceable_id', 'source'),
            'settings.manufacturer_attribute_name' => _p('storage::requests.user.source.attributes.settings.manufacturer_attribute_name', 'manufacturer attribute name'),
            'settings.default_tax_rate' => _p('storage::requests.user.source.attributes.settings.default_tax_rate', 'default tax rate'),
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
            'sourceable_id.unique' => _p('storage::requests.user.source.messages.source_already_exist', 'This source already exists.'),
            'settings.default_tax_rate.between' => _p('storage::requests.user.source.messages.number_outside_range', 'The given number is outside the allowed range.'),
        ];
    }
}
