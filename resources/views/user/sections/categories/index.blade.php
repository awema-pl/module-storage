@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.category.meta_title', 'Shops') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.category.meta_description', 'User warehouse categories on the system.'))

@push('head')

@endpush

@section('title')
    {{ _p('storage::pages.user.category.headline', 'Categories') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.category.add_category', 'Connect category') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.category.categories', 'Category') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.category.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="categories_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.category.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="name" label="{{ _p('storage::pages.user.category.name', 'Name') }}"></tb-column>
                                <tb-column name="parent_category" label="{{ _p('storage::pages.user.category.parent_category', 'Parent category') }}">
                                    <template slot-scope="col">
                                        <template v-if="col.data.parent">
                                            @{{ col.data.parent_crumbs }}
                                        </template>
                                    </template>
                                </tb-column>
                                <tb-column name="external_id" label="{{ _p('storage::pages.user.category.external_id', 'External ID') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.category.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.category.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.category.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editCategory', data: col.data}); AWEMA.emit('modal::edit_category:open')">
                                                {{_p('storage::pages.user.category.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteCategory', data: col.data}); AWEMA.emit('modal::delete_category:open')">
                                                {{_p('storage::pages.user.category.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.category.add_category', 'Connect category') }}">
        <form-builder name="add" url="{{ route('storage.user.category.store') }}" send-text="{{ _p('storage::pages.user.category.add', 'Add') }}"
                      @sended="AWEMA.emit('content::categories_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.category.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-input name="name" label="{{ _p('storage::pages.user.category.name', 'Name') }}"></fb-input>
                     <fb-select name="parent_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.category.parent_category', 'Parent category') }}">
                     </fb-select>
                     <fb-input name="external_id" label="{{ _p('storage::pages.user.category.external_id', 'External ID') }}"></fb-input>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_category" class="modal_formbuilder" title="{{ _p('storage::pages.user.category.edit_category', 'Edit category') }}">
        <form-builder name="edit_category" url="{{ route('storage.user.category.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::categories_table:update')"
                      send-text="{{ _p('storage::pages.user.category.save', 'Save') }}" store-data="editCategory">
            <div v-if="AWEMA._store.state.forms['edit_category']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.category.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editCategory.warehouse && AWEMA._store.state.editCategory.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_category'] && AWEMA._store.state.forms['edit_category'].fields.warehouse_id">
                    <fb-input name="name" label="{{ _p('storage::pages.user.category.name', 'Name') }}"></fb-input>
                    <fb-select name="parent_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.category.select_category_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_category'].fields.warehouse_id + '&exclude_id=' + AWEMA._store.state.editCategory.id + '&include_id=' + (AWEMA._store.state.editCategory.parent && AWEMA._store.state.editCategory.parent.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.category.parent_category', 'Parent category') }}"
                               :auto-fetch-value="AWEMA._store.state.editCategory.parent && AWEMA._store.state.editCategory.parent.id">
                    </fb-select>
                    <fb-input name="external_id" label="{{ _p('storage::pages.user.category.external_id', 'External ID') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_category" class="modal_formbuilder" title="{{  _p('storage::pages.user.category.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.category.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::categories_table:update')"
                      send-text="{{ _p('storage::pages.user.category.confirm', 'Confirm') }}" store-data="deleteCategory"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
