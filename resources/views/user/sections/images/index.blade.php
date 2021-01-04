@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.image.meta_title', 'Images') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.image.meta_description', 'User warehouse images on the system.'))

@push('head')

@endpush

@section('body_class', 'storage_images')

@section('title')
    {{ _p('storage::pages.user.image.headline', 'Images') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.image.add_image', 'Add image') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.image.images', 'Images') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.image.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="images_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.image.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="name" label="{{ _p('storage::pages.user.image.name', 'Name') }}">
                                    <template slot-scope="col">
                                        <div>@{{ col.data.name }}</div>
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.image.default_category', 'Default category') }}:</span> @{{ col.data.default_category.crumbs }}
                                        </div>
                                        <div v-if="col.data.manufacturer" class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.image.manufacturer', 'Manufacturer') }}:</span> @{{ col.data.manufacturer.name }}
                                        </div>
                                        <div class="tf-size-small">
                                            <span v-if="col.data.ean">
                                                <span class="cl-caption">{{ _p('storage::pages.user.image.ean', 'EAN') }}:</span> @{{ col.data.ean }}
                                            </span>
                                            <span v-if="col.data.sku" :class="{'ml-4': col.data.ean}">
                                                <span class="cl-caption">{{ _p('storage::pages.user.image.sku', 'SKU') }}:</span> @{{ col.data.sku }}
                                            </span>
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="information" label="{{ _p('storage::pages.user.image.information', 'Information') }}">
                                    <template slot-scope="col">
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.image.stock', 'Stock') }}:</span> @{{ col.data.stock }}
                                        </div>
                                        <div class="tf-size-small">
                                           <span class="cl-caption">{{ _p('storage::pages.user.image.availability', 'Availability') }}:</span> @{{ col.data.availability_name }}
                                        </div>
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.image.brutto_price', 'Brutto price') }}:</span> @{{ col.data.brutto_price }}
                                        </div>
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.image.tax_rate', 'Tax rate') }}:</span> @{{ col.data.tax_rate }}%
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="external_id" label="{{ _p('storage::pages.user.image.external_id', 'External ID') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.image.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.image.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.image.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editImage', data: col.data}); AWEMA.emit('modal::edit_image:open')">
                                                {{_p('storage::pages.user.image.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteImage', data: col.data}); AWEMA.emit('modal::delete_image:open')">
                                                {{_p('storage::pages.user.image.delete', 'Delete')}}
                                            </cm-button>
                                        </context-menu>
                                    </template>
                                </tb-column>
                            </table-builder>
                            <paginate-builder v-if="table.data"
                                :meta="table.meta"
                            ></paginate-builder>
                        </template>
                        @include('indigo-layout::components.base.loading')
                        @include('indigo-layout::components.base.empty')
                        @include('indigo-layout::components.base.error')
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.image.add_image', 'Add image') }}">
        <form-builder name="add" url="{{ route('storage.user.image.store') }}" send-text="{{ _p('storage::pages.user.image.add', 'Add') }}"
                      @sended="AWEMA.emit('content::images_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.image.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="default_category_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.image.default_category', 'Default category') }}">
                     </fb-select>
                     <fb-select name="manufacturer_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.manufacturer.select_manufacturer_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.image.manufacturer', 'Manufacturer') }}">
                     </fb-select>
                     <fb-input name="name" label="{{ _p('storage::pages.user.image.name', 'Name') }}"></fb-input>
                     <fb-input name="ean" label="{{ _p('storage::pages.user.image.ean', 'EAN') }}"></fb-input>
                     <fb-input name="sku" label="{{ _p('storage::pages.user.image.sku', 'SKU') }}"></fb-input>
                     <fb-input name="stock" type="number" min="0" label="{{ _p('storage::pages.user.image.stock', 'Stock') }}"></fb-input>
                     <fb-select name="availability" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.image.select_availability') }}'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.image.availability', 'Availability') }}">
                     </fb-select>
                     <fb-input name="brutto_price" type="number" min="0" max="99999999" step="0.0001" label="{{ _p('storage::pages.user.image.brutto_price', 'Brutto price') }}"></fb-input>
                     <fb-input name="tax_rate" type="number" min="0" max="100" label="{{ _p('storage::pages.user.image.tax_rate', 'Tax rate') }}"></fb-input>
                     <fb-input name="external_id" label="{{ _p('storage::pages.user.image.external_id', 'External ID') }}"></fb-input>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_image" class="modal_formbuilder" title="{{ _p('storage::pages.user.image.edit_image', 'Edit image') }}">
        <form-builder name="edit_image" url="{{ route('storage.user.image.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::images_table:update')"
                      send-text="{{ _p('storage::pages.user.image.save', 'Save') }}" store-data="editImage">
            <div v-if="AWEMA._store.state.forms['edit_image']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.image.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editImage.warehouse && AWEMA._store.state.editImage.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_image'] && AWEMA._store.state.forms['edit_image'].fields.warehouse_id">
                    <fb-select name="default_category_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_image'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editImage.default_category && AWEMA._store.state.editImage.default_category.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.image.default_category', 'Default category') }}"
                               :auto-fetch-value="AWEMA._store.state.editImage.default_category && AWEMA._store.state.editImage.default_category.id">
                    </fb-select>
                    <fb-select name="manufacturer_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.manufacturer.select_manufacturer_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_image'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editImage.manufacturer && AWEMA._store.state.editImage.manufacturer.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.image.manufacturer', 'Manufacturer') }}"
                               :auto-fetch-value="AWEMA._store.state.editImage.manufacturer && AWEMA._store.state.editImage.manufacturer.id">
                    </fb-select>
                    <fb-input name="name" label="{{ _p('storage::pages.user.image.name', 'Name') }}"></fb-input>
                    <fb-input name="ean" label="{{ _p('storage::pages.user.image.ean', 'EAN') }}"></fb-input>
                    <fb-input name="sku" label="{{ _p('storage::pages.user.image.sku', 'SKU') }}"></fb-input>
                    <fb-input name="stock" type="number" min="0" label="{{ _p('storage::pages.user.image.stock', 'Stock') }}"></fb-input>
                    <fb-select name="availability" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.image.select_availability') }}'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.image.availability', 'Availability') }}"
                               :auto-fetch-value="AWEMA._store.state.editImage.availability">
                    </fb-select>
                    <fb-input name="brutto_price" type="number" min="0" max="99999999" step="0.0001" label="{{ _p('storage::pages.user.image.brutto_price', 'Brutto price') }}"></fb-input>
                    <fb-input name="tax_rate" type="number" min="0" max="100" label="{{ _p('storage::pages.user.image.tax_rate', 'Tax rate') }}"></fb-input>
                    <fb-input name="external_id" label="{{ _p('storage::pages.user.image.external_id', 'External ID') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_image" class="modal_formbuilder" title="{{  _p('storage::pages.user.image.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.image.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::images_table:update')"
                      send-text="{{ _p('storage::pages.user.image.confirm', 'Confirm') }}" store-data="deleteImage"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
