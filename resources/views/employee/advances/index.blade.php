@extends('admin.layouts.app')

@section('title', __('trans.all advances list'))

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
                        <div class="col-xs-6">
                            <h3 style="margin: 0; padding: 0; display: inline">{{__('trans.all advances list') . ' ' . $employeeName}}</h3>
{{--                            <h3 style="margin: 0; padding: 0; display: inline">{{ request('branch_id')?__('trans.all advances list').'['.$branchName.']': __('trans.all advances list')}}</h3>--}}
                        </div>
                        <div class="col-xs-6">
                            @if(request('employee_id'))
                                @if (Auth::user()->hasPermission('create-advances'))
                                    <a href="{{ route('employee.advances.create', ['employee_id' => request()->get('employee_id')]) }}" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> {{ __('trans.create') }}</a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-4 col-xs-4">
                            <form class="form-inline">
                                <div class="form-group">
                                    <input type="text" id="start_date" value="" placeholder="من تاريخ" autocomplete="off" style="width: 90px">
                                </div>
                                <div class="form-group">
                                    <input type="text" id="end_date" value="" placeholder="الى تاريخ" autocomplete="off" style="width: 90px">
                                </div>
                                <button type="button" id="filter_date_button" class="btn btn-success btn-xs">بحث <i class="fa fa-search"></i> </button>
                                <button type="button" id="refresh_date_button" class="btn btn-warning btn-xs">اعادة تحميل <i class="fa fa-refresh"></i> </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped']) !!}
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="scheduled_advances_model">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ __('trans.schedule') }}</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('trans.status') }}</th>
                                <th>{{ __('trans.amount') }}</th>
                                <th>{{ __('trans.date of payment') }}</th>
                                {{--                            <th></th>--}}
                            </tr>
                        </thead>
                        <tbody id="scheduled_advances_body"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">اغلاق</button>
{{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@push('links')
    <!-- Datatable Bootstrap Css Files -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/datatableButtonsCssFiles/buttons.dataTables.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
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
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <!-- Custom Function -->
    <script>
        $('#start_date').datepicker({
            autoclose: true,
            todayBtn: "linked",
            format: "yyyy-mm-dd"
        });
        $('#end_date').datepicker({
            autoclose: true,
            todayBtn: "linked",
            format: "yyyy-mm-dd"
        });


        $('#filter_date_button').on('click', function () {
            let start_date = $('#start_date').val();
            let end_date    = $('#end_date').val();
            let table       = $('#advancedatatable-table');
            table.on('preXhr.dt', function (e, settings, data) {
                data.start_date  = start_date;
                data.end_date  = end_date;
                console.log(data.start_date, data.end_date)
            })

            table.DataTable().ajax.reload();
            $('#refresh_date_button').on('click', function () {
                table.on('preXhr.dt', function (e, settings, data) {
                    data.start_date  = '';
                    data.end_date  = '';
                })
                table.DataTable().ajax.reload();
            })
        })

    </script>
    <script>

        // Show Confirm Message For Delete Any Item
        function showDeleteMessage (){
            let deleteMessage = '{{ __('trans.are you sure delete this') }}';
            return confirm(deleteMessage);
        }
    </script>

    <script>
        $(document).on('click', '.scheduled_advances_button', function (e) {
            $('.scheduled_advances_body').empty();
            // e.preventDefault();
            let advance_id = $(this).data('id');
            let url = route('employee.scheduling_details');

            $.ajax({
                url: url,
                method: 'GET',
                data: {advance_id: advance_id},
                success: function (data) {
                    let content = '';
                    $.each(data.scheduled_advances, function (key, value) {
                        let class_status = value.status == 'غير مسددة'? 'bg-danger': 'bg-success';
                        content += '<tr>';
                        content += '<td class="'+class_status+'">'+value.status+'</td>';
                        content += '<td>'+value.amount+'</td>';
                        content += '<td>'+value.date_of_payment ?value.date_of_payment : "" +'</td>';
                       // $('.scheduled_advances_body').append(value.amount)
                        // content += '<option value="'+value.id+'">'+value.name+'</option>';
                        content += '</tr>';
                    });
                    $('#scheduled_advances_body').html(content);
                }
            })


        })
    </script>
@endpush


