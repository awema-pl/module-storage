@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.duplicate_product.meta_title', 'Duplicate products') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.duplicate_product.meta_description', 'Duplicate products of the warehouse user in the system.'))

@push('head')

@endpush

@section('title')
    {{ _p('storage::pages.user.duplicate_product.headline', 'Duplicate products') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.duplicate_product.add_duplicate_product', 'Add product') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.duplicate_product.duplicate_products', 'Duplicate products') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.duplicate_product.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="products_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.duplicate_product.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="product" label="{{ _p('storage::pages.user.duplicate_product.product', 'Product') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.product.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="product" label="{{ _p('storage::pages.user.duplicate_product.duplicate_product', 'Duplicate product') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.duplicate_product.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.duplicate_product.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.duplicate_product.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editDuplicateProduct', data: col.data}); AWEMA.emit('modal::edit_duplicate_product:open')">
                                                {{_p('storage::pages.user.duplicate_product.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteDuplicateProduct', data: col.data}); AWEMA.emit('modal::delete_duplicate_product:open')">
                                                {{_p('storage::pages.user.duplicate_product.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.duplicate_product.add_duplicate_product', 'Add a product to a category') }}">
        <form-builder name="add" url="{{ route('storage.user.duplicate_product.store') }}" send-text="{{ _p('storage::pages.user.duplicate_product.add', 'Add') }}"
                      @sended="AWEMA.emit('content::products_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.duplicate_product.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="product_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.duplicate_product.product', 'Product') }}">
                     </fb-select>
                     <fb-select name="duplicate_product_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&exclude_id=' + AWEMA._store.state.forms['add'].fields.product_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.duplicate_product.duplicate_product', 'Duplicate product') }}">
                     </fb-select>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_duplicate_product" class="modal_formbuilder" title="{{ _p('storage::pages.user.duplicate_product.edit_duplicate_product', 'Edit product') }}">
        <form-builder name="edit_duplicate_product" url="{{ route('storage.user.duplicate_product.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::products_table:update')"
                      send-text="{{ _p('storage::pages.user.duplicate_product.save', 'Save') }}" store-data="editDuplicateProduct">
            <div v-if="AWEMA._store.state.forms['edit_duplicate_product']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.duplicate_product.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editDuplicateProduct.warehouse && AWEMA._store.state.editDuplicateProduct.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_duplicate_product'] && AWEMA._store.state.forms['edit_duplicate_product'].fields.warehouse_id">

                    <fb-select name="product_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_duplicate_product'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editDuplicateProduct.product && AWEMA._store.state.editDuplicateProduct.product.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.duplicate_product.product', 'Product') }}"
                               :auto-fetch-value="AWEMA._store.state.editDuplicateProduct.product && AWEMA._store.state.editDuplicateProduct.product.id">
                    </fb-select>
                    <fb-select name="duplicate_product_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_duplicate_product'].fields.warehouse_id + '&exclude_id=' + AWEMA._store.state.editDuplicateProduct.product.id + '&include_id=' + (AWEMA._store.state.editDuplicateProduct.duplicate_product && AWEMA._store.state.editDuplicateProduct.duplicate_product.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.duplicate_product.duplicate_product', 'Duplicate product') }}"
                               :auto-fetch-value="AWEMA._store.state.editDuplicateProduct.duplicate_product && AWEMA._store.state.editDuplicateProduct.duplicate_product.id">
                    </fb-select>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_duplicate_product" class="modal_formbuilder" title="{{  _p('storage::pages.user.duplicate_product.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.duplicate_product.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::products_table:update')"
                      send-text="{{ _p('storage::pages.user.duplicate_product.confirm', 'Confirm') }}" store-data="deleteDuplicateProduct"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
