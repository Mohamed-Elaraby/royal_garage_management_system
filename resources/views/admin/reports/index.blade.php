@extends('admin.layouts.app')

@section('title', __('trans.reports'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header" style="margin: 20px 0">
                    <div class="row">
                        <div class="col-xs-12">
                            <h4 style="text-align: center">{{ __('trans.reports') }}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <form class="form-inline" style=" text-align: center; margin-top: 20px">
                                <!-- Date and time range -->
                                <div class="form-group">
{{--                                    <label>Date range button:</label>--}}

                                    <div class="input-group">
                                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                                            <span>
                                              <i class="fa fa-calendar"></i> اختر تاريخ البحث
                                            </span>
                                            <i class="fa fa-caret-down"></i>
                                        </button>
                                    </div>
                                    <div class="input-group">
                                        <select id="branch_id" class="form-control">
                                            <option value="">كل الفروع</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch -> id }}">{{ $branch -> name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- /.form group -->
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="width: 100%">
                    <div style="width: 50%; margin: auto">
                        <table class="table table-responsive" id="title_result"></table>
                        <table class="table table-responsive table-bordered" id="filter_result"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <style>
        #filter_result th, #filter_result td
        {
            border: #000000 solid 1px;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')

    <!-- date-range-picker -->
    <script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <script>
        $(document).ready(function () {
            let startDate,
                endDate,
                branch_id,
                branch_selected_name,
                table = $('#filter_result'),
                title_table = $('#title_result');

            //Date range as a button
            $('#daterange-btn').daterangepicker(
                {
                    ranges   : {
                        'اليوم'       : [moment(), moment()],
                        'امس'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'اخر 7 ايام' : [moment().subtract(6, 'days'), moment()],
                        'اخر 30 يوم': [moment().subtract(29, 'days'), moment()],
                        'الشهر الحالى'  : [moment().startOf('month'), moment().endOf('month')],
                        'الشهر الماضي'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'العام الحالى'  : [moment().startOf('year'), moment().endOf('year')],
                        'العام الماضي'  : [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate  : moment()
                },
                function (start, end) {
                    // function getFilterResultByDate() {
                    startDate = start.format('YYYY-M-D');
                    endDate = end.format('YYYY-M-D');
                    // $('#daterange-btn span').text(start.format('YYYY-M-D') + ' - ' + end.format('YYYY-M-D'));
                    getFilterResult(startDate, endDate);
                }
            )
            function getFilterResult(start_d, end_d) {
                start_d = startDate || '';
                end_d = endDate || '';
                branch_id = $('#branch_id').val();
                branch_selected_name = $('#branch_id option:selected').text();
                let total_purchases_URL = '{{ route("admin.purchaseOrders.index", ['status' => 'close']) }}'+'?'+'start_date='+start_d+'&end_date='+end_d+'&branch_id='+branch_id;
                let total_sales_URL = '{{ route("admin.saleOrders.index", ['status' => 'close']) }}'+'?'+'start_date='+start_d+'&end_date='+end_d+'&branch_id='+branch_id;
                let total_expenses_URL = '{{ route("admin.expenses.index") }}'+'?'+'start_date='+start_d+'&end_date='+end_d+'&branch_id='+branch_id;
                console.log(start_d, end_d)
            $.ajax({
                url: "{{ route('admin.getDataSearch') }}",
                method: 'GET',
                data: {startDate: start_d, endDate: end_d, branch_id: branch_id},
                success: function (data) {
                    // console.log(data)
                    let separator = start_d && end_d ?'-': '';
                    // console.log( start_d.replaceAll('-', '/'))
                    let title ='<tr>\n' +
                        '<td>بحث عن طريق:</td>\n' +
                        '<td>'+ end_d.replaceAll(' - ','/') + separator + start_d.replaceAll('-','/') +'</td>\n' +
                        '<td>'+ branch_selected_name +'</td>\n' +
                        '       </tr>';
                    let content = '<tr>' +
                        '<th>التعريف</th>' +
                        '<th>المبلغ</th>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><a target="_blank" href="'+total_purchases_URL+'">اجمالى المشتريات</a></td>'+
                        '<td>'+data.total_purchases+' دولار </td>' +
                        '</tr>'+
                        '<tr>' +
                        '<td><a target="_blank" href="'+total_sales_URL+'">اجمالى المبيعات</a></td>'+
                        '<td>'+data.total_sales+' دولار </td>' +
                        '</tr>'+
                        '<tr>' +
                        '<td><a target="_blank" href="'+total_expenses_URL+'">اجمالى المصروفات</a></td>'+
                        '<td>'+data.total_expenses+' دولار </td>' +
                        '</tr>'+
                        '<tr>' +
                        '<td>اجمالى الضريبة</td>' +
                        '<td>'+data.total_vat+' دولار </td>' +
                        '</tr>'+
                        '<tr>' +
                        '<td>الاجمالى</td>' +
                        '<td>'+data.total+' دولار </td>' +
                        '</tr>';

                    table.empty();
                    table.append(content);
                    title_table.empty();
                    title_table.append(title);
                }
            })
        }

            $('#branch_id').on('change', function () {
                getFilterResult();
            })
        })
    </script>

@endpush


