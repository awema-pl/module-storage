<?php

namespace AwemaPL\Storage\User\Sections\Descriptions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDescription extends FormRequest
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
            'type' => ['required', 'string', 'max:255', Rule::unique(config('storage.database.tables.storage_descriptions'))->where(function ($query) {
                return $query->where('product_id', $this->product_id);
            })->ignore($this->id)],
            'value' => 'nullable|string|max:16777215',
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
            'warehouse_id' => _p('storage::requests.user.description.attributes.warehouse_id', 'warehouse'),
            'product_id' =>  _p('storage::requests.user.description.attributes.product_id', 'product'),
            'type' =>  _p('storage::requests.user.description.attributes.type', 'type'),
            'value' =>  _p('storage::requests.user.description.attributes.value', 'description'),
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
            'type.unique' => _p('storage::requests.user.description.messages.type_already_exists', 'This description type already exists.'),
        ];
    }
}
