@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.warehouse.meta_title', 'Warehouses') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.warehouse.meta_description', 'User warehouses in the system.'))

@push('head')

@endpush

@section('body_class', 'storage_warehouses')

@section('title')
    {{ _p('storage::pages.user.warehouse.headline', 'Warehouses') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.warehouse.add_warehouse', 'Add warehouse') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.warehouse.warehouses', 'Warehouses') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.warehouse.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="warehouses_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="{{ _p('storage::pages.user.warehouse.name', 'Name') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.warehouse.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.warehouse.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.warehouse.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'generateDuplicateProductWarehouse', data: col.data}); AWEMA.emit('modal::generate_duplicate_product_warehouse:open')">
                                                {{_p('storage::pages.user.warehouse.generate_duplicate_product', 'Generate duplicate products')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editWarehouse', data: col.data}); AWEMA.emit('modal::edit_warehouse:open')">
                                                {{_p('storage::pages.user.warehouse.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteWarehouse', data: col.data}); AWEMA.emit('modal::delete_warehouse:open')">
                                                {{_p('storage::pages.user.warehouse.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.warehouse.add_warehouse', 'Add warehouse') }}">
        <form-builder name="add" url="{{ route('storage.user.warehouse.store') }}" send-text="{{ _p('storage::pages.user.warehouse.add', 'Add') }}"
                      @sended="AWEMA.emit('content::warehouses_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-input name="name" label="{{ _p('storage::pages.user.warehouse.name', 'Name') }}"></fb-input>
                 <h5 class="cl-caption mt-20 mb-0">{{ _p('storage::pages.user.warehouse.duplicate_product_settings', 'Duplicate product settings') }}</h5>
                 <div class="mt-15">
                     <fb-switcher name="duplicate_product_settings.external_id" label="{{ _p('storage::pages.user.warehouse.generate_duplicates_via_external_id', 'Generate duplicates via external ID.') }}"></fb-switcher>
                     <fb-switcher name="duplicate_product_settings.gtin" label="{{ _p('storage::pages.user.warehouse.generate_duplicates_via_gtin', 'Generate duplicates via GTIN.') }}"></fb-switcher>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="generate_duplicate_product_warehouse" class="modal_formbuilder" title="{{  _p('storage::pages.user.warehouse.are_you_sure_generate_duplicate_products', 'Are you sure generate duplicate products?') }}">
        <form-builder :edited="true" url="{{route('storage.user.duplicate_product.generate_duplicate_by_warehouse') }}/{id}" method="post"
                      @sended="AWEMA.emit('content::products_table:update')"
                      send-text="{{ _p('storage::pages.user.warehouse.confirm', 'Confirm') }}" store-data="generateDuplicateProductWarehouse"
                      disabled-dialog>

        </form-builder>
    </modal-window>

    <modal-window name="edit_warehouse" class="modal_formbuilder" title="{{ _p('storage::pages.user.warehouse.edit_warehouse', 'Edit warehouse') }}">
        <form-builder name="edit_warehouse" url="{{ route('storage.user.warehouse.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::warehouses_table:update')"
                      send-text="{{ _p('storage::pages.user.warehouse.save', 'Save') }}" store-data="editWarehouse">
            <div v-if="AWEMA._store.state.forms['edit_warehouse']">
                <fb-input name="name" label="{{ _p('storage::pages.user.warehouse.name', 'Name') }}"></fb-input>
                <h5 class="cl-caption mt-20 mb-0">{{ _p('storage::pages.user.warehouse.duplicate_product_settings', 'Duplicate product settings') }}</h5>
                <div class="mt-15">
                    <fb-switcher name="duplicate_products.external_id" label="{{ _p('storage::pages.user.warehouse.generate_duplicates_via_external_id', 'Generate duplicates via external ID.') }}"></fb-switcher>
                    <fb-switcher name="duplicate_products.gtin" label="{{ _p('storage::pages.user.warehouse.generate_duplicates_via_gtin', 'Generate duplicates via GTIN.') }}"></fb-switcher>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_warehouse" class="modal_formbuilder" title="{{  _p('storage::pages.user.warehouse.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.warehouse.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::warehouses_table:update')"
                      send-text="{{ _p('storage::pages.user.warehouse.confirm', 'Confirm') }}" store-data="deleteWarehouse"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
