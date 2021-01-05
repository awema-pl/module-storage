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
            'sourceable_id.unique' => _p('storage::requests.user.source.messages.source_already_exist', 'This source already exists.')
        ];
    }
}
