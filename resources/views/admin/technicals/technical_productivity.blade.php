@extends('admin.layouts.app')

@section('title', __('trans.technical productivity'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header" style="margin: 20px 0">
                    <div class="row">
                        <div class="col-xs-12">
                            <h4 style="text-align: center">{{ __('trans.technical productivity') }}</h4>
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
                                </div>

                                <!-- /.form group -->
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="width: 100%">
                    <div class="myTable" style="width: 50%; margin: auto; position: relative">
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
        .message
        {
            text-align: center;
            background-color: #d7be11;
            padding: 0px 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 5px;
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
                let page_URL = new URL(window.location.href);
                let technical_id = page_URL.searchParams.get("technical_id");
                let productivity_for_technical_URL = '{{ route("admin.check.index") }}'+'?'+'start_date='+start_d+'&end_date='+end_d+'&technical_id='+technical_id;
                // console.log(start_d, end_d, technical_id)
            $.ajax({
                url: "{{ route('admin.get_technical_productivity_by_ajax') }}",
                method: 'GET',
                data: {startDate: start_d, endDate: end_d, technical_id: technical_id},
                success: function (data) {
                    // console.log(data)
                    let separator = start_d && end_d ?' - ': '';
                    // console.log( start_d.replaceAll('-', '/'))
                    let title ='<tr>\n' +
                        '<td>بحث عن الفترة:</td>\n' +
                        '<td>'+ end_d.replaceAll('-','/') + separator + start_d.replaceAll('-','/') +'</td>\n' +
                        '       </tr>';
                    let content = '<tr>' +
                        '<th>عدد الفحوصات</th>' +
                        '<th>نصيب الفنى من اجور اليد</th>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><a target="_blank" href="'+productivity_for_technical_URL+'">'+data.technical_checks.length+'</a></td>'+
                        // '<td></td>'+
                        '<td>'+data.hands_fees+'</td>'+
                        '</tr>';

                    table.empty();
                    table.append(content);
                    title_table.empty();
                    title_table.append(title);
                }
            }).done(function(  ) {
                let alertMessage = '<div class="message">\n' +
                    '<span>\n' +
                    'تم تحميل البيانات. \n'+
                    '</span>\n' +
                    '</div>';
                $('.myTable').append(alertMessage);
                setTimeout(function() {$('.message').hide();}, 500);
            });
        }

            $('#branch_id').on('change', function () {
                getFilterResult();
            })
        })
    </script>

@endpush


