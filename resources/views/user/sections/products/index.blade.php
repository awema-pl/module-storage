@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.product.meta_title', 'Products') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.product.meta_description', 'User warehouse products on the system.'))

@push('head')

@endpush

@section('title')
    {{ _p('storage::pages.user.product.headline', 'Products') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.product.add_product', 'Add product') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.product.products', 'Products') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.product.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="products_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.product.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="active" label="{{ _p('storage::pages.user.product.active', 'Active') }}">
                                    <template slot-scope="col">
                                        <div class="cl-caption">
                                            <template v-if="col.data.active">
                                                {{ _p('storage::pages.user.product.yes', 'Yes') }}
                                            </template>
                                            <template v-else>
                                                {{ _p('storage::pages.user.product.no', 'No') }}
                                            </template>
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="name" label="{{ _p('storage::pages.user.product.name', 'Name') }}">
                                    <template slot-scope="col">
                                        <div style="max-width: 350px;">
                                            <div>@{{ col.data.name }}</div>
                                            <div class="tf-size-small">
                                                <span class="cl-caption">{{ _p('storage::pages.user.product.default_category', 'Default category') }}:</span> @{{ col.data.default_category.crumbs }}
                                            </div>
                                            <div v-if="col.data.manufacturer" class="tf-size-small">
                                                <span class="cl-caption">{{ _p('storage::pages.user.product.manufacturer', 'Manufacturer') }}:</span> @{{ col.data.manufacturer.name }}
                                            </div>
                                            <div class="tf-size-small">
                                                <span v-if="col.data.ean">
                                                    <span class="cl-caption">{{ _p('storage::pages.user.product.ean', 'EAN') }}:</span> @{{ col.data.ean }}
                                                </span>
                                                <span v-if="col.data.sku" :class="{'ml-4': col.data.ean}">
                                                    <span class="cl-caption">{{ _p('storage::pages.user.product.sku', 'SKU') }}:</span> @{{ col.data.sku }}
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="information" label="{{ _p('storage::pages.user.product.information', 'Information') }}">
                                    <template slot-scope="col">
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.product.stock', 'Stock') }}:</span> @{{ col.data.stock }}
                                        </div>
                                        <div class="tf-size-small">
                                           <span class="cl-caption">{{ _p('storage::pages.user.product.availability', 'Availability') }}:</span> @{{ col.data.availability_name }}
                                        </div>
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.product.brutto_price', 'Brutto price') }}:</span> @{{ col.data.brutto_price }}
                                        </div>
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.product.tax_rate', 'Tax rate') }}:</span> @{{ col.data.tax_rate }}%
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="external_id" label="{{ _p('storage::pages.user.product.external_id', 'External ID') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.product.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.product.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.product.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editProduct', data: col.data}); AWEMA.emit('modal::edit_product:open')">
                                                {{_p('storage::pages.user.product.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteProduct', data: col.data}); AWEMA.emit('modal::delete_product:open')">
                                                {{_p('storage::pages.user.product.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.product.add_product', 'Add product') }}">
        <form-builder name="add" url="{{ route('storage.user.product.store') }}" send-text="{{ _p('storage::pages.user.product.add', 'Add') }}"
                      @sended="AWEMA.emit('content::products_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.product.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="default_category_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.product.default_category', 'Default category') }}">
                     </fb-select>
                     <fb-select name="manufacturer_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.manufacturer.select_manufacturer_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.product.manufacturer', 'Manufacturer') }}">
                     </fb-select>
                     <fb-switcher name="active" label="{{ _p('storage::pages.user.product.active', 'Active') }}"></fb-switcher>
                     <fb-input name="name" label="{{ _p('storage::pages.user.product.name', 'Name') }}"></fb-input>
                     <fb-input name="ean" label="{{ _p('storage::pages.user.product.ean', 'EAN') }}"></fb-input>
                     <fb-input name="sku" label="{{ _p('storage::pages.user.product.sku', 'SKU') }}"></fb-input>
                     <fb-input name="stock" type="number" min="0" label="{{ _p('storage::pages.user.product.stock', 'Stock') }}"></fb-input>
                     <fb-select name="availability" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_availability') }}'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.product.availability', 'Availability') }}">
                     </fb-select>
                     <fb-input name="brutto_price" type="number" min="0" max="99999999" step="0.0001" label="{{ _p('storage::pages.user.product.brutto_price', 'Brutto price') }}"></fb-input>
                     <fb-input name="tax_rate" type="number" min="0" max="100" label="{{ _p('storage::pages.user.product.tax_rate', 'Tax rate') }}"></fb-input>
                     <fb-input name="external_id" label="{{ _p('storage::pages.user.product.external_id', 'External ID') }}"></fb-input>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_product" class="modal_formbuilder" title="{{ _p('storage::pages.user.product.edit_product', 'Edit product') }}">
        <form-builder name="edit_product" url="{{ route('storage.user.product.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::products_table:update')"
                      send-text="{{ _p('storage::pages.user.product.save', 'Save') }}" store-data="editProduct">
            <div v-if="AWEMA._store.state.forms['edit_product']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.product.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editProduct.warehouse && AWEMA._store.state.editProduct.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_product'] && AWEMA._store.state.forms['edit_product'].fields.warehouse_id">
                    <fb-select name="default_category_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_product'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editProduct.default_category && AWEMA._store.state.editProduct.default_category.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.product.default_category', 'Default category') }}"
                               :auto-fetch-value="AWEMA._store.state.editProduct.default_category && AWEMA._store.state.editProduct.default_category.id">
                    </fb-select>
                    <fb-select name="manufacturer_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.manufacturer.select_manufacturer_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_product'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editProduct.manufacturer && AWEMA._store.state.editProduct.manufacturer.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.product.manufacturer', 'Manufacturer') }}"
                               :auto-fetch-value="AWEMA._store.state.editProduct.manufacturer && AWEMA._store.state.editProduct.manufacturer.id">
                    </fb-select>
                    <fb-switcher name="active" label="{{ _p('storage::pages.user.product.active', 'Active') }}"></fb-switcher>
                    <fb-input name="name" label="{{ _p('storage::pages.user.product.name', 'Name') }}"></fb-input>
                    <fb-input name="ean" label="{{ _p('storage::pages.user.product.ean', 'EAN') }}"></fb-input>
                    <fb-input name="sku" label="{{ _p('storage::pages.user.product.sku', 'SKU') }}"></fb-input>
                    <fb-input name="stock" type="number" min="0" label="{{ _p('storage::pages.user.product.stock', 'Stock') }}"></fb-input>
                    <fb-select name="availability" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_availability') }}'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.product.availability', 'Availability') }}"
                               :auto-fetch-value="AWEMA._store.state.editProduct.availability">
                    </fb-select>
                    <fb-input name="brutto_price" type="number" min="0" max="99999999" step="0.0001" label="{{ _p('storage::pages.user.product.brutto_price', 'Brutto price') }}"></fb-input>
                    <fb-input name="tax_rate" type="number" min="0" max="100" label="{{ _p('storage::pages.user.product.tax_rate', 'Tax rate') }}"></fb-input>
                    <fb-input name="external_id" label="{{ _p('storage::pages.user.product.external_id', 'External ID') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_product" class="modal_formbuilder" title="{{  _p('storage::pages.user.product.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.product.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::products_table:update')"
                      send-text="{{ _p('storage::pages.user.product.confirm', 'Confirm') }}" store-data="deleteProduct"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
