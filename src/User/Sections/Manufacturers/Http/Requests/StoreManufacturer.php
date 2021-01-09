<?php

namespace AwemaPL\Storage\User\Sections\Manufacturers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreManufacturer extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique(config('storage.database.tables.storage_manufacturers'))->where(function ($query) {
                return $query->where('warehouse_id', $this->warehouse_id)
                    ->where('source_id', $this->source_id);
            })],
            'image_url' => 'nullable|string|max:255',
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
            'warehouse_id' => _p('storage::requests.user.manufacturer.attributes.warehouse_id', 'warehouse'),
            'name' => _p('storage::requests.user.manufacturer.attributes.name', 'name'),
            'image_url' => _p('storage::requests.user.manufacturer.attributes.image_url', 'image web address'),
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
            'name.unique' => _p('storage::requests.user.manufacturer.messages.manufacturer_already_exists', 'This manufacturer already exists.'),
        ];
    }
}
