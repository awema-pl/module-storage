@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.manufacturer.meta_title', 'Manufacturers') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.manufacturer.meta_description', 'User warehouse manufacturers on the system.'))

@push('head')

@endpush

@section('body_class', 'storage_manufacturers')

@section('title')
    {{ _p('storage::pages.user.manufacturer.headline', 'Manufacturers') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.manufacturer.add_manufacturer', 'Add manufacturer') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.manufacturer.manufacturers', 'Manufacturers') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.manufacturer.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="manufacturers_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.manufacturer.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="name" label="{{ _p('storage::pages.user.manufacturer.name', 'Name') }}"></tb-column>
                                <tb-column name="image_url" label="{{ _p('storage::pages.user.manufacturer.image_url', 'Image web address') }}">
                                    <template slot-scope="col">
                                        <template v-if="col.data.image_url">
                                            <img class="manufacturer-image tf-img" :src="col.data.image_url" alt="col.data.name"/>
                                        </template>
                                    </template>
                                </tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.manufacturer.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.manufacturer.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.manufacturer.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editManufacturer', data: col.data}); AWEMA.emit('modal::edit_manufacturer:open')">
                                                {{_p('storage::pages.user.manufacturer.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteManufacturer', data: col.data}); AWEMA.emit('modal::delete_manufacturer:open')">
                                                {{_p('storage::pages.user.manufacturer.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.manufacturer.add_manufacturer', 'Add manufacturer') }}">
        <form-builder name="add" url="{{ route('storage.user.manufacturer.store') }}" send-text="{{ _p('storage::pages.user.manufacturer.add', 'Add') }}"
                      @sended="AWEMA.emit('content::manufacturers_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.manufacturer.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-input name="name" label="{{ _p('storage::pages.user.manufacturer.name', 'Name') }}"></fb-input>
                     <fb-input name="image_url" label="{{ _p('storage::pages.user.manufacturer.image_url', 'Image web address') }}"></fb-input>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_manufacturer" class="modal_formbuilder" title="{{ _p('storage::pages.user.manufacturer.edit_manufacturer', 'Edit manufacturer') }}">
        <form-builder name="edit_manufacturer" url="{{ route('storage.user.manufacturer.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::manufacturers_table:update')"
                      send-text="{{ _p('storage::pages.user.manufacturer.save', 'Save') }}" store-data="editManufacturer">
            <div v-if="AWEMA._store.state.forms['edit_manufacturer']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.manufacturer.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editManufacturer.warehouse && AWEMA._store.state.editManufacturer.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_manufacturer'] && AWEMA._store.state.forms['edit_manufacturer'].fields.warehouse_id">
                    <fb-input name="name" label="{{ _p('storage::pages.user.manufacturer.name', 'Name') }}"></fb-input>
                    <fb-input name="image_url" label="{{ _p('storage::pages.user.manufacturer.image_url', 'Image web address') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_manufacturer" class="modal_formbuilder" title="{{  _p('storage::pages.user.manufacturer.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.manufacturer.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::manufacturers_table:update')"
                      send-text="{{ _p('storage::pages.user.manufacturer.confirm', 'Confirm') }}" store-data="deleteManufacturer"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
