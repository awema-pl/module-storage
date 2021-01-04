@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.description.meta_title', 'Descriptions') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.description.meta_description', 'User warehouse descriptions on the system.'))

@push('head')

@endpush

@section('title')
    {{ _p('storage::pages.user.description.headline', 'Descriptions') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.description.add_description', 'Add description') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.description.descriptions', 'Descriptions') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.description.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="descriptions_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.description.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="product" label="{{ _p('storage::pages.user.description.product', 'Product') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.product.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="type_name" label="{{ _p('storage::pages.user.description.type', 'Typ') }}"></tb-column>
                                <tb-column name="value_truncate" label="{{ _p('storage::pages.user.description.description', 'Description') }}"></tb-column>
                                 <tb-column name="created_at" label="{{ _p('storage::pages.user.description.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.description.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.description.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editDescription', data: col.data}); AWEMA.emit('modal::edit_description:open')">
                                                {{_p('storage::pages.user.description.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteDescription', data: col.data}); AWEMA.emit('modal::delete_description:open')">
                                                {{_p('storage::pages.user.description.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.description.add_description', 'Add description') }}">
        <form-builder name="add" url="{{ route('storage.user.description.store') }}" send-text="{{ _p('storage::pages.user.description.add', 'Add') }}"
                      @sended="AWEMA.emit('content::descriptions_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.description.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="product_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.description.product', 'Product') }}">
                     </fb-select>
                     <fb-select name="type" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.description.select_type') }}'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.description.type', 'Typ') }}"
                                :auto-fetch-value="'default'">
                     </fb-select>
                     <fb-textarea name="value" label="{{ _p('storage::pages.user.description.description', 'Desscription') }}"></fb-textarea>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_description" class="modal_formbuilder" title="{{ _p('storage::pages.user.description.edit_description', 'Edit description') }}">
        <form-builder name="edit_description" url="{{ route('storage.user.description.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::descriptions_table:update')"
                      send-text="{{ _p('storage::pages.user.description.save', 'Save') }}" store-data="editDescription">
            <div v-if="AWEMA._store.state.forms['edit_description']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.description.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editDescription.warehouse && AWEMA._store.state.editDescription.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_description'] && AWEMA._store.state.forms['edit_description'].fields.warehouse_id">
                    <fb-select name="product_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_description'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editDescription.product && AWEMA._store.state.editDescription.product.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.description.product', 'Product') }}"
                               :auto-fetch-value="AWEMA._store.state.editDescription.product && AWEMA._store.state.editDescription.product.id">
                    </fb-select>
                    <fb-select name="type" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.description.select_type') }}'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.description.type', 'Typ') }}"
                               :auto-fetch-value="AWEMA._store.state.editDescription.type">
                    </fb-select>
                    <fb-textarea name="value" label="{{ _p('storage::pages.user.description.description', 'Desscription') }}"></fb-textarea>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_description" class="modal_formbuilder" title="{{  _p('storage::pages.user.description.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.description.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::descriptions_table:update')"
                      send-text="{{ _p('storage::pages.user.description.confirm', 'Confirm') }}" store-data="deleteDescription"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
