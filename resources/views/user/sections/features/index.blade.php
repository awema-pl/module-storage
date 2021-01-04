@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.feature.meta_title', 'Features') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.feature.meta_description', 'User warehouse features on the system.'))

@push('head')

@endpush

@section('body_class', 'storage_features')

@section('title')
    {{ _p('storage::pages.user.feature.headline', 'Features') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.feature.add_feature', 'Add feature') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.feature.features', 'Features') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.feature.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="features_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.feature.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="product" label="{{ _p('storage::pages.user.feature.product', 'Product') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.product.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="variant" label="{{ _p('storage::pages.user.feature.variant', 'Variant') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.variant.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="type_name" label="{{ _p('storage::pages.user.feature.type', 'Type') }}"></tb-column>
                                <tb-column name="name" label="{{ _p('storage::pages.user.feature.name', 'Name') }}"></tb-column>
                                <tb-column name="value" label="{{ _p('storage::pages.user.feature.value', 'Value') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.feature.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.feature.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.feature.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editFeature', data: col.data}); AWEMA.emit('modal::edit_feature:open')">
                                                {{_p('storage::pages.user.feature.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteFeature', data: col.data}); AWEMA.emit('modal::delete_feature:open')">
                                                {{_p('storage::pages.user.feature.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.feature.add_feature', 'Add feature') }}">
        <form-builder name="add" url="{{ route('storage.user.feature.store') }}" send-text="{{ _p('storage::pages.user.feature.add', 'Add') }}"
                      @sended="AWEMA.emit('content::features_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.feature.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="product_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.feature.product', 'Product') }}">
                     </fb-select>
                     <fb-input name="name" label="{{ _p('storage::pages.user.feature.name', 'Name') }}"></fb-input>
                    <fb-input name="value" label="{{ _p('storage::pages.user.feature.value', 'Value') }}"></fb-input>
                     <fb-select name="type" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.feature.select_type') }}'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.feature.type', 'Typ') }}"
                                :auto-fetch-value="'default'">
                     </fb-select>
                 </div>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.product_id">
                     <fb-select name="variant_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.variant.select_variant_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&product_id=' + AWEMA._store.state.forms['add'].fields.product_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.feature.variant', 'Variant') }}">
                     </fb-select>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_feature" class="modal_formbuilder" title="{{ _p('storage::pages.user.feature.edit_feature', 'Edit feature') }}">
        <form-builder name="edit_feature" url="{{ route('storage.user.feature.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::features_table:update')"
                      send-text="{{ _p('storage::pages.user.feature.save', 'Save') }}" store-data="editFeature">
            <div v-if="AWEMA._store.state.forms['edit_feature']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.feature.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editFeature.warehouse && AWEMA._store.state.editFeature.warehouse.id">
                </fb-select>
                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_feature'] && AWEMA._store.state.forms['edit_feature'].fields.warehouse_id">
                    <fb-select name="product_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_description'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editDescription.product && AWEMA._store.state.editDescription.product.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.feature.product', 'Product') }}"
                               :auto-fetch-value="AWEMA._store.state.editDescription.product && AWEMA._store.state.editDescription.product.id">
                    </fb-select>
                    <fb-input name="name" label="{{ _p('storage::pages.user.feature.name', 'Name') }}"></fb-input>
                    <fb-input name="value" label="{{ _p('storage::pages.user.feature.value', 'Value') }}"></fb-input>
                    <fb-select name="type" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.feature.select_type') }}'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.feature.type', 'Typ') }}"
                               :auto-fetch-value="AWEMA._store.state.editDescription.type">
                    </fb-select>
                </div>
                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_feature'] && AWEMA._store.state.forms['edit_feature'].fields.product_id">
                    <fb-select name="variant_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.variant.select_variant_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_feature'].fields.warehouse_id + '&product_id=' + AWEMA._store.state.forms['edit_feature'].fields.product_id + '&include_id=' + (AWEMA._store.state.editFeature.variant && AWEMA._store.state.editFeature.variant.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.feature.variant', 'Variant') }}"
                               :auto-fetch-value="AWEMA._store.state.editFeature.variant && AWEMA._store.state.editFeature.variant.id">
                    </fb-select>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_feature" class="modal_formbuilder" title="{{  _p('storage::pages.user.feature.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.feature.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::features_table:update')"
                      send-text="{{ _p('storage::pages.user.feature.confirm', 'Confirm') }}" store-data="deleteFeature"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
