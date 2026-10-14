@extends('admin.layouts.app')

@section('title', __('trans.all roles list'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="error_messages text-center">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('delete'))
                        <div class="alert alert-danger">
                            {{ session('delete') }}
                        </div>
                    @endif
                </div>
                <div class="card-header" style="margin: 20px 0">
                    <div class="row">
{{--                        <div class="col-xs-6">--}}
{{--                            <h3 style="margin: 0; padding: 0; display: inline">{{ request('branch_id')?__('trans.all roles list').'['.$branchName.']': __('trans.all roles list')}}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="col-xs-6">--}}
{{--                            @if (Auth::user()->hasPermission('create-rolesManager'))--}}
{{--                                <a href="{{ route('admin.rolesManager.create') }}" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> {{ __('trans.create') }}</a>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation"><a href="#elements" aria-controls="elements" role="tab" data-toggle="tab">عناصر الموقع</a></li>
                            <li role="presentation" class="active"><a href="#roles" aria-controls="roles" role="tab" data-toggle="tab">الادوار</a></li>
                         </ul>

                        <!-- Tab panes -->
                        <div style="overflow-y: scroll; height: 90vh; ">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane" id="elements">
                                    <form id="create_new_element_form" action="{{ route('admin.roleElements.store') }}"
                                          method="post">
                                        @csrf
                                        <label for="element_name">Name</label>
                                        <input type="text" class="form-text" name="name" id="element_name">
                                        <input type="submit" class="btn btn-success btn-sm" value="Create Element">
                                        {{--                                    <button class="btn btn-success btn-sm">Create</button>--}}
                                    </form>
                                    <table class="table table-responsive">
                                        <thead>
                                        <th>#</th>
                                        <th>Element Name</th>
                                        <th>action</th>
                                        </thead>
                                        <tbody id="elements_body"></tbody>
                                    </table>
                                </div> <!-- Tap 1 -->
                                <div role="tabpanel" class="tab-pane active" id="roles">
                                    <form id="create_new_role" action="{{ route('admin.rolesManager.store') }}"
                                          method="post">
                                        @csrf
                                        <label for="role_name">Name</label>
                                        <input type="text" class="form-text" name="name" id="role_name">
                                        <input type="submit" class="btn btn-success btn-sm" value="Create Role">
                                    </form>
                                    <table class="table table-responsive">
                                        <thead>
                                        <th>#</th>
                                        <th>role Name</th>
                                        <th>Permissions</th>
{{--                                        <th>action</th>--}}
                                        </thead>
                                        <tbody id="roles_list_body"></tbody>
                                    </table>

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><div id="permission_name_in_model_title"></div> Permissions
                                                        <input type="checkbox" name="selectAll" id="selectAll">
                                                    </h4>
                                                </div>
                                                <div class="modal-body role_permissions_body">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
{{--                                                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- Tap 2 -->
                            </div>
                        </div>

                    </div>
{{--                    {!! $dataTable->table(['class' => 'table table-bordered table-striped']) !!}--}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')
    <!-- Datatable Bootstrap Css Files -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/datatableButtonsCssFiles/buttons.dataTables.min.css') }}">
    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <style>
            .date_dir_setting
            {
                direction:ltr;
                text-align: right;
            }
        </style>
    @endif
@endpush

@push('scripts')

    <!-- Datatable Bootstrap JavaScript Files -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
{{--    {!! $dataTable->scripts() !!}--}}


    <!-- Custom Function -->
    <script src="{{ asset('js/includes/roles_and_permissions/global_functions.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/get_role_permissions.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/load_site_elements.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/load_roles_list.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/create_new_site_element.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/create_new_role.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/select_all_permissions.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/select_group_permissions.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/select_permission.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/delete_site_element.js') }}"></script>
    <script src="{{ asset('js/includes/roles_and_permissions/delete_role.js') }}"></script>

    <script>
        // Show Confirm Message For Delete Any Item
        function showDeleteMessage (){
            let deleteMessage = '{{ __('trans.are you sure delete this') }}';
            return confirm(deleteMessage);
        }
    </script>
@endpush
