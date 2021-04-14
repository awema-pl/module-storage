
@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.source.meta_title', 'Sources') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.source.meta_description', 'User product sources in the application.'))

@push('head')

@endpush

@section('title')
    {{ _p('storage::pages.user.source.headline', 'Sources') }}
@endsection

@section('create_button')
    <button class="frame__header-add" title="{{ _p('storage::pages.user.source.adding_source', 'Adding source') }}"
            @click="AWEMA.emit('modal::add_source:open')"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.source.sources', 'Sources') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.source.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="sources_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.source.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="sourceable_type" label="{{ _p('storage::pages.user.source.sourceable_type', 'Source type') }}">
                                    <template slot-scope="col">
                                        <span v-if="col.data.sourceable_type_name" class="badge badge_grass">@{{ col.data.sourceable_type_name }}</span>
                                        <span v-else class="badge badge_warn">{{ _p('storage::pages.user.source.no_connection_source', 'No connection to source')  }}</span>
                                    </template>
                                </tb-column>
                                <tb-column name="source_name" label="{{ _p('storage::pages.user.source.source_name', 'Source name') }}">
                                    <template slot-scope="col">
                                        <span class="cl-caption">@{{ col.data.source_name }}</span>
                                    </template>
                                </tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.source.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.source.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.source.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editSource', data: col.data}); AWEMA.emit('modal::edit_source:open')">
                                                {{_p('storage::pages.user.source.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'importProductSource', data: col.data}); AWEMA.emit('modal::import_product_source:open')">
                                                {{_p('storage::pages.user.source.import_products', 'Import products')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'updateProductSource', data: col.data}); AWEMA.emit('modal::update_product_source:open')">
                                                {{_p('storage::pages.user.source.update_products', 'Update products')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteSource', data: col.data}); AWEMA.emit('modal::delete_source:open')">
                                                {{_p('storage::pages.user.source.delete', 'Delete')}}
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

    <modal-window name="add_source" class="modal_formbuilder" title="{{ _p('storage::pages.user.source.adding_source', 'Adding source') }}">
        <form-builder name="add_source" url="{{ route('storage.user.source.store') }}"
                      @sended="AWEMA.emit('content::sources_table:update')"
                      send-text="{{ _p('storage::pages.user.source.add', 'Add') }}" disabled-dialog>
            <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                       :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                       placeholder-text=" " label="{{ _p('storage::pages.user.source.warehouse', 'Warehouse') }}">
            </fb-select>
            <h5 class="cl-caption mt-20 mb-0">{{ _p('storage::pages.user.source.source_products', 'Source of products') }}</h5>
            <div class="mt-10">
                <fb-select name="sourceable_type" disabled-search :multiple="false" open-fetch options-value="id" options-name="name"
                           :url="'{{ route('storage.user.source.select_sourceable_type') }}'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.source.select_sourceable_type', 'Select the type of source') }}">
                </fb-select>
            </div>
            <div class="mt-10" v-if="AWEMA._store.state.forms['add_source'] && AWEMA._store.state.forms['add_source'].fields.sourceable_type">
                <fb-select name="sourceable_id" disabled-search :multiple="false" open-fetch options-value="id" options-name="name"
                           :url="'{{ route('storage.user.source.select_sourceable_id') }}?sourceable_type=' + AWEMA._store.state.forms['add_source'].fields.sourceable_type"
                           placeholder-text=" " label="{{ _p('storage::pages.user.source.select_source', 'Select a source') }}">
                </fb-select>
            </div>
            <h5 class="cl-caption mt-20 mb-0">{{ _p('storage::pages.user.source.settings', 'Settings') }}</h5>
            <div class="mt-10">
                <fb-input name="settings.manufacturer_attribute_name" size-sm label="{{ _p('storage::pages.user.source.manufacturer_attribute_name', 'Manufacturer attribute name') }}"></fb-input>
                <fb-input name="settings.default_tax_rate" :value="'23'" type="number" min="0" max="100" size-sm label="{{ _p('storage::pages.user.source.default_tax_rate', 'Default tax rate') }}"></fb-input>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="import_product_source" class="modal_formbuilder" title="{{  _p('storage::pages.user.source.are_you_sure_import_products', 'Are you sure to import the products?') }}">
        <form-builder :edited="true" url="{{route('storage.user.source.import_product') }}/{id}"
                      name="import_product_source" @sended="AWEMA.emit('content::sources_table:update')"
                      send-text="{{ _p('storage::pages.user.source.confirm', 'Confirm') }}" store-data="importProductSource"
                      disabled-dialog>
        </form-builder>
    </modal-window>

    <modal-window name="update_product_source" class="modal_formbuilder" title="{{  _p('storage::pages.user.source.updating_products', 'Updating products') }}">
        <form-builder :edited="true" url="{{route('storage.user.source.update_product') }}/{id}"
                      name="update_product_source" @sended="AWEMA.emit('content::sources_table:update')"
                      send-text="{{ _p('storage::pages.user.source.update', 'Update') }}" store-data="updateProductSource"
                      disabled-dialog>
            <h5 class="cl-caption mb-0">{{ _p('storage::pages.user.source.please_select_properties_update', 'Please select the product properties to be updated.') }}</h5>
            <div class="mt-15">
                <fb-switcher name="stock" label="{{ _p('storage::pages.user.source.stock', 'Stock') }}"></fb-switcher>
                <fb-switcher name="availability" label="{{ _p('storage::pages.user.source.availability', 'Availability') }}"></fb-switcher>
                <fb-switcher name="brutto_price" label="{{ _p('storage::pages.user.source.brutto_price', 'Brutto price') }}"></fb-switcher>
                <fb-switcher name="name" label="{{ _p('storage::pages.user.source.name', 'Name') }}"></fb-switcher>
                <fb-switcher name="description" label="{{ _p('storage::pages.user.source.description', 'Description') }}"></fb-switcher>
                <fb-switcher name="features" label="{{ _p('storage::pages.user.source.features', 'Features') }}"></fb-switcher>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_source" class="modal_formbuilder" title="{{ _p('storage::pages.user.source.source_editing', 'Source editing') }}">
        <form-builder name="edit_source" url="{{ route('storage.user.source.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::sources_table:update')"
                      send-text="{{ _p('storage::pages.user.source.save', 'Save') }}" store-data="editSource">
            <div v-if="AWEMA._store.state.editSource">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.source.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editSource.warehouse && AWEMA._store.state.editSource.warehouse.id">
                </fb-select>
                <h5 class="cl-caption mt-20 mb-0">{{ _p('storage::pages.user.source.source_products', 'Source of products') }}</h5>
                <div class="mt-10">
                    <fb-select name="sourceable_type" disabled-search :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.source.select_sourceable_type') }}'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.source.select_sourceable_type', 'Select the type of source.') }}"
                               :auto-fetch-value="AWEMA._store.state.editSource.sourceable_type">
                    </fb-select>
                </div>
                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_source'] && AWEMA._store.state.forms['edit_source'].fields.sourceable_type">
                    <fb-select name="sourceable_id" disabled-search :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.source.select_sourceable_id') }}?sourceable_type=' + AWEMA._store.state.forms['edit_source'].fields.sourceable_type"
                               placeholder-text=" " label="{{ _p('storage::pages.user.source.select_source', 'Select a source') }}"
                               :auto-fetch-value="AWEMA._store.state.editSource.sourceable_id">
                    </fb-select>
                </div>
                <h5 class="cl-caption mt-20 mb-0">{{ _p('storage::pages.user.source.settings', 'Settings') }}</h5>
                <div class="mt-10">
                    <fb-input name="settings.manufacturer_attribute_name" size-sm label="{{ _p('storage::pages.user.source.manufacturer_attribute_name', 'Manufacturer attribute name') }}"></fb-input>
                    <fb-input name="settings.default_tax_rate" type="number" min="0" max="100" size-sm label="{{ _p('storage::pages.user.source.default_tax_rate', 'Default tax rate') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_source" class="modal_formbuilder" title="{{  _p('storage::pages.user.source.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.source.destroy') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::sources_table:update')"
                      send-text="{{ _p('storage::pages.user.source.confirm', 'Confirm') }}" store-data="deleteSource"
                      disabled-dialog>
        </form-builder>
    </modal-window>
@endsection
