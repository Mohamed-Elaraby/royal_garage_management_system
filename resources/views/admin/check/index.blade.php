@extends('admin.layouts.app')

@section('title',  __('trans.all check list'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="error_messages text-center">
                    @if (session('status_success'))
                        <div class="alert alert-success">
                            {{ session('status_success') }}
                        </div>
                    @endif
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
                        <div class="col-xs-8">
                            @if(request('check_status_id'))
                                <h4 style="margin: 0; padding: 0; display: inline">
                                    [ {{ __('trans.all check list').' - '.$checkStatusName }} ] {{ request('branch_id')? '[ '.$branchName.' ]':'' }}
                                </h4>
                            @elseif(request('branch_id'))
                                <h4 style="margin: 0; padding: 0; display: inline">
                                    [ {{ __('trans.all check list').' - '.$branchName }} ] {{ $carExists? '['.$carExists.']': '' }}
                                </h4>
                            @elseif(request('client_id'))
                                <h4 style="margin: 0; padding: 0; display: inline">
                                    [ {{ __('trans.all check list').' - '.$clientName }} ]
                                </h4>
                            @elseif(request('car_id'))
                                <h4 style="margin: 0; padding: 0; display: inline">
                                    [ {{ __('trans.all check list').' - '.$car -> carType -> name. ' - الخاصة بالعميل '. $car -> client -> name}} ]
                                </h4>
                            @else
                                <h4 style="margin: 0; padding: 0; display: inline"><i
                                        class="fa fa-check"></i> {{ __('trans.all check list') }}
                                </h4>
{{--                                <button id="make_pdf" class="btn btn-success">Make PDF</button>--}}
                            @endif
                        </div>
                        <div class="col-xs-4">
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
                        @if(!request('car_id'))
                            <div class="col-xs-12">
                                <form class="form-inline" style=" text-align: center; margin-top: 20px">
                                    <div class="form-group">
                                        <input type="text" id="search_in_chassis_number" value="" placeholder="بحث برقم الشاسية" autocomplete="off" >
                                    </div>
                                    <button type="button" id="filter_chassis_number_button" class="btn btn-success btn-xs">بحث <i class="fa fa-filter"></i> </button>
                                    <button type="button" id="refresh_chassis_number_button" class="btn btn-warning btn-xs">اعادة تحميل <i class="fa fa-refresh"></i> </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')
    <!-- Datatable Bootstrap Css Files -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/datatableButtonsCssFiles/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <style>
            .date_dir_setting
            {
                direction:ltr;
                text-align: right;
            }
            .management_notes_select_color
            {
                background-color: #00a989 !important;
            }
            .car_status_exit_not_repair_color
            {
                background-color: #eeff00 !important;
            }
            .car_status_exit_under_warranty_color
            {
                background-color: #ffa300 !important;
            }
        </style>
    @else
        <style>
            .management_notes_select_color
            {
                background-color: #00a989 !important;
            }
        </style>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('scripts')
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
    <!-- Script -->
    <script>
        // Show Confirm Message For Delete Any Item
        function showDeleteMessage() {
            let deleteMessage = '{{ __('trans.are you sure delete this') }}';
            return confirm(deleteMessage);
        } // end showDeleteMessage function

        $(document).on('change', '#changeCheckStatus', function () {
            // Show Confirm Message For Change Any Status
            let confirmMessage = '{{ __('trans.are you sure you want to change the scan status') }}';
            let result = confirm(confirmMessage);
            if (result)
            {
                let check_status_id = $(this).children('option:selected').attr('value');
                let check_id = $(this).children('option:selected').attr('data-check-id');
                let url = '{{ route('admin.check.onlyEditCheckStatus') }}';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    cache:false,
                    url: url,
                    data: {check_status_id:check_status_id, check_id:check_id},
                    complete:function (response) {
                        // console.log(response)
                        setTimeout(function(){
                            window.location.reload(true); // you can pass true to reload function to ignore the client cache and reload from the server
                        });
                    }
                }); // end ajax
            } // end if
        }) // end on change

    </script>

    <script>
        $(document).on('change', '#selectAction', function () {
            let url = $(this).children('option:selected').attr('value');
            location.href = url;
        }) // end on change
    </script>
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
            let table       = $('#checkdatatable-table');

            table.on('preXhr.dt', function (e, settings, data) {
                data.start_date  = start_date;
                data.end_date  = end_date;

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

        $('#filter_chassis_number_button').on('click', function () {
            let search_in_chassis_number = $('#search_in_chassis_number').val();
            let table       = $('#checkdatatable-table');

            table.on('preXhr.dt', function (e, settings, data) {
                data.search_in_chassis_number  = search_in_chassis_number;

            })
            table.DataTable().ajax.reload();
            $('#refresh_chassis_number_button').on('click', function () {
                table.on('preXhr.dt', function (e, settings, data) {
                    data.search_in_chassis_number  = '';
                })
                table.DataTable().ajax.reload();
            })

        })

    </script>

@endpush

