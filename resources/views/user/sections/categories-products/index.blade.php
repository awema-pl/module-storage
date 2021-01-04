@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.category_product.meta_title', 'Products in categories') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.category_product.meta_description', 'Products in categories of the warehouse user in the system.'))

@push('head')

@endpush

@section('title')
    {{ _p('storage::pages.user.category_product.headline', 'Products in categories') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.category_product.add_category_product', 'Add product') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.category_product.categories_products', 'Products in categories') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.category_product.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="products_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.category_product.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="category" label="{{ _p('storage::pages.user.category_product.category', 'Category') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.category.crumbs }}
                                    </template>
                                </tb-column>
                                <tb-column name="product" label="{{ _p('storage::pages.user.category_product.product', 'Product') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.product.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.category_product.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.category_product.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editCategoryProduct', data: col.data}); AWEMA.emit('modal::edit_category_product:open')">
                                                {{_p('storage::pages.user.category_product.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteCategoryProduct', data: col.data}); AWEMA.emit('modal::delete_category_product:open')">
                                                {{_p('storage::pages.user.category_product.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.category_product.add_category_product', 'Add a product to a category') }}">
        <form-builder name="add" url="{{ route('storage.user.category_product.store') }}" send-text="{{ _p('storage::pages.user.category_product.add', 'Add') }}"
                      @sended="AWEMA.emit('content::products_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.category_product.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="category_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.category_product.category', 'Category') }}">
                     </fb-select>
                     <fb-select name="product_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.category_product.product', 'Product') }}">
                     </fb-select>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_category_product" class="modal_formbuilder" title="{{ _p('storage::pages.user.category_product.edit_category_product', 'Edit product') }}">
        <form-builder name="edit_category_product" url="{{ route('storage.user.category_product.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::products_table:update')"
                      send-text="{{ _p('storage::pages.user.category_product.save', 'Save') }}" store-data="editCategoryProduct">
            <div v-if="AWEMA._store.state.forms['edit_category_product']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.category_product.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editCategoryProduct.warehouse && AWEMA._store.state.editCategoryProduct.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_category_product'] && AWEMA._store.state.forms['edit_category_product'].fields.warehouse_id">
                    <fb-select name="category_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_category_product'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editCategoryProduct.category && AWEMA._store.state.editCategoryProduct.category.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.category_product.category', 'Category') }}"
                               :auto-fetch-value="AWEMA._store.state.editCategoryProduct.category && AWEMA._store.state.editCategoryProduct.category.id">
                    </fb-select>
                    <fb-select name="product_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_category_product'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editCategoryProduct.product && AWEMA._store.state.editCategoryProduct.product.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.category_product.category', 'Category') }}"
                               :auto-fetch-value="AWEMA._store.state.editCategoryProduct.product && AWEMA._store.state.editCategoryProduct.product.id">
                    </fb-select>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_category_product" class="modal_formbuilder" title="{{  _p('storage::pages.user.category_product.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.category_product.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::products_table:update')"
                      send-text="{{ _p('storage::pages.user.category_product.confirm', 'Confirm') }}" store-data="deleteCategoryProduct"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
