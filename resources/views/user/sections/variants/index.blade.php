@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.variant.meta_title', 'Variants') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.variant.meta_description', 'User warehouse variants on the system.'))

@push('head')

@endpush

@section('title')
    {{ _p('storage::pages.user.variant.headline', 'Variants') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.variant.add_variant', 'Add variant') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.variant.variants', 'Variants') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.variant.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="variants_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.variant.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="product" label="{{ _p('storage::pages.user.variant.product', 'Product') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.product.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="active" label="{{ _p('storage::pages.user.variant.active', 'Active') }}">
                                    <template slot-scope="col">
                                        <div class="cl-caption">
                                            <template v-if="col.data.active">
                                                {{ _p('storage::pages.user.variant.yes', 'Yes') }}
                                            </template>
                                            <template v-else>
                                                {{ _p('storage::pages.user.variant.no', 'No') }}
                                            </template>
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="name" label="{{ _p('storage::pages.user.variant.name', 'Name') }}">
                                    <template slot-scope="col">
                                        <div>@{{ col.data.name }}</div>
                                        <div class="tf-size-small">
                                            <span v-if="col.data.gtin">
                                                <span class="cl-caption">{{ _p('storage::pages.user.variant.gtin', 'GTIN') }}:</span> @{{ col.data.gtin }}
                                            </span>
                                            <span v-if="col.data.sku" :class="{'ml-4': col.data.gtin}">
                                                <span class="cl-caption">{{ _p('storage::pages.user.variant.sku', 'SKU') }}:</span> @{{ col.data.sku }}
                                            </span>
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="information" label="{{ _p('storage::pages.user.variant.information', 'Information') }}">
                                    <template slot-scope="col">
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.variant.stock', 'Stock') }}:</span> @{{ col.data.stock }}
                                        </div>
                                        <div class="tf-size-small">
                                           <span class="cl-caption">{{ _p('storage::pages.user.variant.availability', 'Availability') }}:</span> @{{ col.data.availability_name }}
                                        </div>
                                        <div class="tf-size-small">
                                            <span class="cl-caption">{{ _p('storage::pages.user.variant.brutto_price', 'Brutto price') }}:</span> @{{ col.data.brutto_price }}
                                        </div>
                                    </template>
                                </tb-column>
                                <tb-column name="external_id" label="{{ _p('storage::pages.user.variant.external_id', 'External ID') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.variant.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.variant.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.variant.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editVariant', data: col.data}); AWEMA.emit('modal::edit_variant:open')">
                                                {{_p('storage::pages.user.variant.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteVariant', data: col.data}); AWEMA.emit('modal::delete_variant:open')">
                                                {{_p('storage::pages.user.variant.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.variant.add_variant', 'Add variant') }}">
        <form-builder name="add" url="{{ route('storage.user.variant.store') }}" send-text="{{ _p('storage::pages.user.variant.add', 'Add') }}"
                      @sended="AWEMA.emit('content::variants_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.variant.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="product_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.variant.product', 'Product') }}">
                     </fb-select>
                     <fb-switcher name="active" label="{{ _p('storage::pages.user.variant.active', 'Active') }}"></fb-switcher>
                     <fb-input name="name" label="{{ _p('storage::pages.user.variant.name', 'Name') }}"></fb-input>
                     <fb-input name="gtin" label="{{ _p('storage::pages.user.variant.gtin', 'GTIN') }}"></fb-input>
                     <fb-input name="sku" label="{{ _p('storage::pages.user.variant.sku', 'SKU') }}"></fb-input>
                     <fb-input name="stock" type="number" min="0" label="{{ _p('storage::pages.user.variant.stock', 'Stock') }}"></fb-input>
                     <fb-select name="availability" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_availability') }}'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.variant.availability', 'Availability') }}">
                     </fb-select>
                     <fb-input name="brutto_price" type="number" min="0" max="99999999" step="0.0001" label="{{ _p('storage::pages.user.variant.brutto_price', 'Brutto price') }}"></fb-input>
                    <fb-input name="external_id" label="{{ _p('storage::pages.user.variant.external_id', 'External ID') }}"></fb-input>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_variant" class="modal_formbuilder" title="{{ _p('storage::pages.user.variant.edit_variant', 'Edit variant') }}">
        <form-builder name="edit_variant" url="{{ route('storage.user.variant.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::variants_table:update')"
                      send-text="{{ _p('storage::pages.user.variant.save', 'Save') }}" store-data="editVariant">
            <div v-if="AWEMA._store.state.forms['edit_variant']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.variant.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editVariant.warehouse && AWEMA._store.state.editVariant.warehouse.id">
                </fb-select>
                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_variant'] && AWEMA._store.state.forms['edit_variant'].fields.warehouse_id">
                    <fb-select name="product_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_variant'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editVariant.product && AWEMA._store.state.editVariant.product.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.variant.product', 'Product') }}"
                               :auto-fetch-value="AWEMA._store.state.editVariant.product && AWEMA._store.state.editVariant.product.id">
                    </fb-select>
                    <fb-switcher name="active" label="{{ _p('storage::pages.user.variant.active', 'Active') }}"></fb-switcher>
                    <fb-input name="name" label="{{ _p('storage::pages.user.variant.name', 'Name') }}"></fb-input>
                    <fb-input name="gtin" label="{{ _p('storage::pages.user.variant.gtin', 'GTIN') }}"></fb-input>
                    <fb-input name="sku" label="{{ _p('storage::pages.user.variant.sku', 'SKU') }}"></fb-input>
                    <fb-input name="stock" type="number" min="0" label="{{ _p('storage::pages.user.variant.stock', 'Stock') }}"></fb-input>
                    <fb-select name="availability" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_availability') }}'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.variant.availability', 'Availability') }}"
                               :auto-fetch-value="AWEMA._store.state.editVariant.availability">
                    </fb-select>
                    <fb-input name="brutto_price" type="number" min="0" max="99999999" step="0.0001" label="{{ _p('storage::pages.user.variant.brutto_price', 'Brutto price') }}"></fb-input>
                   <fb-input name="external_id" label="{{ _p('storage::pages.user.variant.external_id', 'External ID') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_variant" class="modal_formbuilder" title="{{  _p('storage::pages.user.variant.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.variant.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::variants_table:update')"
                      send-text="{{ _p('storage::pages.user.variant.confirm', 'Confirm') }}" store-data="deleteVariant"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
