@extends('indigo-layout::main')

@section('meta_title', _p('storage::pages.user.image.meta_title', 'Images') . ' - ' . config('app.name'))
@section('meta_description', _p('storage::pages.user.image.meta_description', 'User warehouse images on the system.'))

@push('head')

@endpush

@section('body_class', 'storage_images')

@section('title')
    {{ _p('storage::pages.user.image.headline', 'Images') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add:open')" title="{{ _p('storage::pages.user.image.add_image', 'Add image') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('storage::pages.user.image.images', 'Images') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('storage.user.image.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="images_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="warehouse" label="{{ _p('storage::pages.user.image.warehouse', 'Warehouse') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.warehouse.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="product" label="{{ _p('storage::pages.user.image.product', 'Product') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.product.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="variant" label="{{ _p('storage::pages.user.image.variant', 'Variant') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.variant.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="url" label="{{ _p('storage::pages.user.image.url', 'Web address') }}">
                                    <template slot-scope="col">
                                        <template v-if="col.data.url">
                                            <img class="manufacturer-image tf-img" :src="col.data.url" :alt="(col.data.variant) ? col.data.variant.name : col.data.name"/>
                                        </template>
                                    </template>
                                </tb-column>
                                <tb-column name="external_id" label="{{ _p('storage::pages.user.image.external_id', 'External ID') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('storage::pages.user.image.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('storage::pages.user.image.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('storage::pages.user.image.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editImage', data: col.data}); AWEMA.emit('modal::edit_image:open')">
                                                {{_p('storage::pages.user.image.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteImage', data: col.data}); AWEMA.emit('modal::delete_image:open')">
                                                {{_p('storage::pages.user.image.delete', 'Delete')}}
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

    <modal-window name="add" class="modal_formbuilder" title="{{ _p('storage::pages.user.image.add_image', 'Add image') }}">
        <form-builder name="add" url="{{ route('storage.user.image.store') }}" send-text="{{ _p('storage::pages.user.image.add', 'Add') }}"
                      @sended="AWEMA.emit('content::images_table:update')">
             <div v-if="AWEMA._store.state.forms['add']">
                 <fb-select name="warehouse_id" :multiple="false" open-fetch options-value="id" options-name="name"
                            :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                            placeholder-text=" " label="{{ _p('storage::pages.user.image.warehouse', 'Warehouse') }}">
                 </fb-select>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.warehouse_id">
                     <fb-select name="product_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.image.product', 'Product') }}">
                     </fb-select>
                     <fb-input name="url" label="{{ _p('storage::pages.user.image.url', 'Web address') }}"></fb-input>
                    <fb-input name="external_id" label="{{ _p('storage::pages.user.image.external_id', 'External ID') }}"></fb-input>
                 </div>
                 <div class="mt-10" v-if="AWEMA._store.state.forms['add'] && AWEMA._store.state.forms['add'].fields.product_id">
                     <fb-select name="variant_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('storage.user.variant.select_variant_id') }}?warehouse_id=' + AWEMA._store.state.forms['add'].fields.warehouse_id + '&product_id=' + AWEMA._store.state.forms['add'].fields.product_id + '&q=%s'"
                                placeholder-text=" " label="{{ _p('storage::pages.user.image.variant', 'Variant') }}">
                     </fb-select>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_image" class="modal_formbuilder" title="{{ _p('storage::pages.user.image.edit_image', 'Edit image') }}">
        <form-builder name="edit_image" url="{{ route('storage.user.image.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::images_table:update')"
                      send-text="{{ _p('storage::pages.user.image.save', 'Save') }}" store-data="editImage">
            <div v-if="AWEMA._store.state.forms['edit_image']">
                <fb-select name="warehouse_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name" disabled="disabled"
                           :url="'{{ route('storage.user.warehouse.select_warehouse_id') }}?q=%s'"
                           placeholder-text=" " label="{{ _p('storage::pages.user.image.warehouse', 'Warehouse') }}"
                           :auto-fetch-value="AWEMA._store.state.editImage.warehouse && AWEMA._store.state.editImage.warehouse.id">
                </fb-select>

                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_image'] && AWEMA._store.state.forms['edit_image'].fields.warehouse_id">
                    <fb-select name="product_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.product.select_product_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_description'].fields.warehouse_id + '&include_id=' + (AWEMA._store.state.editDescription.product && AWEMA._store.state.editDescription.product.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.image.product', 'Product') }}"
                               :auto-fetch-value="AWEMA._store.state.editDescription.product && AWEMA._store.state.editDescription.product.id">
                    </fb-select>
                    <fb-input name="url" label="{{ _p('storage::pages.user.image.url', 'Web address') }}"></fb-input>
                    <fb-input name="external_id" label="{{ _p('storage::pages.user.image.external_id', 'External ID') }}"></fb-input>
                </div>
                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_image'] && AWEMA._store.state.forms['edit_image'].fields.product_id">
                    <fb-select name="variant_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('storage.user.variant.select_variant_id') }}?warehouse_id=' + AWEMA._store.state.forms['edit_image'].fields.warehouse_id + '&product_id=' + AWEMA._store.state.forms['edit_image'].fields.product_id + '&include_id=' + (AWEMA._store.state.editImage.variant && AWEMA._store.state.editImage.variant.id) + '&q=%s'"
                               placeholder-text=" " label="{{ _p('storage::pages.user.image.variant', 'Variant') }}"
                               :auto-fetch-value="AWEMA._store.state.editImage.variant && AWEMA._store.state.editImage.variant.id">
                    </fb-select>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_image" class="modal_formbuilder" title="{{  _p('storage::pages.user.image.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('storage.user.image.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::images_table:update')"
                      send-text="{{ _p('storage::pages.user.image.confirm', 'Confirm') }}" store-data="deleteImage"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
