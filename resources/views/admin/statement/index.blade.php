@extends('admin.layouts.app')

@section('title', 'Statement')

@section('content')

        <div class="row">
            <div class="text-center">
                <div class="col-xs-offset-3 col-xs-6">
                    <div class="">
                        <div class="row">
                            <div class="col-xs-12">
                                <form class="form-inline" style=" text-align: center; margin-top: 20px">
                                    <!-- Date and time range -->
                                    <div class="form-group">
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
                                                @if(auth() -> user() -> branch_id === null)
                                                    <option value="">اختر الفرع</option>
                                                @endif
                                                @foreach ($branch_list as $branch)
                                                    <option value="{{ $branch -> id }}">{{ $branch -> display_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.form group -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="show_data"></div>

@endsection
@push('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/receipt/css/orders.css') }}">
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
{{--    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">--}}
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .table_striped_class tr:nth-child(odd) {
            background-color: #d4cfcf;
        }
        .card_details_edit:hover
        {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/receipt/js/mainReceipt.js') }}"></script>

    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

    <!-- date-range-picker -->
    <script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <script>
        let startDate,
            endDate;
            //Date range as a button

            let branch_id = $('#branch_id').val();
            $('#daterange-btn').daterangepicker(
                {
                    ranges: {
                        'اليوم': [moment(), moment()],
                        'امس': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'اخر 7 ايام': [moment().subtract(6, 'days'), moment()],
                        'اخر 30 يوم': [moment().subtract(29, 'days'), moment()],
                        'الشهر الحالى': [moment().startOf('month'), moment().endOf('month')],
                        'الشهر الماضي': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'العام الحالى': [moment().startOf('year'), moment().endOf('year')],
                        'العام الماضي': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    startDate = start.format('YYYY-M-D');
                    endDate = end.format('YYYY-M-D');
                    getFilterResult(startDate, endDate);


                }
            );

        function getFilterResult(start_d, end_d) {
            start_d = startDate || '';
            end_d = endDate || '';
            let branch_id = $('#branch_id').val();
            getStatementByBranchAndDate(start_d, end_d, branch_id);
        }

        $('#branch_id').on('change', function () {
            getFilterResult();
        });

        function getStatementByBranchAndDate(startDate, endDate, branchId) {
            $.ajax({
                url: "{{ route('admin.statement.table') }}",
                method: 'GET',
                data: {startDate: startDate, endDate: endDate, branchId: branchId},
                success: function (data) {
                    let show_data = $('.show_data');
                    show_data.empty();
                    show_data.html(data);
                }
            })
        }

        $(document).on('click', '#update_button', function () {
            let id = $('#statement_id').val();
            let hand_labour = $('#hand_labour').val() || null;
            let new_parts = $('#new_parts').val() || null;
            let used_parts = $('#used_parts').val() || null;
            let tax_amount = $('#total_vat').val() || null;
            let total_imports = $('#total_imports_area').text();
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let branch_id = $('#branch_id').val();
            let url = "{{ route('admin.statement.update', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'PUT',
                data: {'card_details_hand_labour': hand_labour, 'card_details_new_parts': new_parts, 'card_details_used_parts': used_parts, 'tax_amount': tax_amount, 'total_imports': total_imports},
                success: function (data) {
                    getStatementByBranchAndDate(start_date, end_date, branch_id);
                    console.log(data)
                    // getStatementByBranchAndDate();
                    // getStatementByBranchAndDate(start_d, end_d, branch_id);
                    // content.empty();
                    // content.html(data);
                }
            })
        })
    </script>

    <script>


        function makeToFixedNumber(num) {
            return Math.trunc(num*100)/100;
        }

        function showCardDetails (cardDetailsClass)
        {
            let that = cardDetailsClass;
            let row = that.closest('tr');
            let imports_cash = makeToFixedNumber(row.find('.imports_cash').text()) || 0 ;
            let imports_network = makeToFixedNumber(row.find('.imports_network').text()) || 0 ;
            let imports_bank_transfer = makeToFixedNumber(row.find('.imports_bank_transfer').text()) || 0 ;
            let total_imports = makeToFixedNumber(imports_cash + imports_network + imports_bank_transfer);
            let total_imports_result = $('.total_imports_area');
            let hand_labour = row.find('.hand_labour_edit').text();
            let new_parts = row.find('.new_parts_edit').text();
            let used_parts = row.find('.used_parts_edit').text();
            let total_vat = row.find('.tax_edit').text();
            let id = row.attr('id');
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let branch_id = $('#branch_id').val();
            let content = $('.card_details_content');
            let url = "{{ route('admin.statement.edit', ':id') }}";
            url = url.replace(':id', id);

            console.log('imports_cash = ' + typeof imports_cash + ' - ' + imports_cash);
            console.log('imports_network = ' + typeof imports_network + ' - ' + imports_network);
            console.log('imports_bank_transfer = ' + typeof imports_bank_transfer + ' - ' + imports_bank_transfer);
            console.log('total_imports = ' + typeof total_imports + ' - ' + total_imports);
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    'id': id,
                    'hand_labour': hand_labour,
                    'new_parts': new_parts,
                    'used_parts': used_parts,
                    'total_imports': total_imports,
                    'total_vat': total_vat,
                    'start_date': start_date,
                    'end_date': end_date,
                    'branch_id': branch_id,
                },
                success: function (data) {
                    content.empty();
                    content.html(data);
                }
            })
        }

        $(document).on('click', '.card_details_edit', function () {
            showCardDetails ($(this));
        });

        $(document).on('mouseenter', '.card_details_edit', function () {
            make_row_hover($(this));
        });

        $(document).on('mouseleave', '.card_details_edit', function () {
            remove_row_hover($(this));
        });

        function make_row_hover (cardDetailsClass)
        {
            let that = cardDetailsClass;
            let row = that.closest('tr');
            let card_details_title = $('.card_details_title');
            that.css({'color': 'red'});
            row.find('.card_details_item').css({'color': 'red'});
            card_details_title.css({'color': 'red'});
        }

        function remove_row_hover (cardDetailsClass)
        {
            let that = cardDetailsClass;
            let row = that.closest('tr');
            let card_details_title = $('.card_details_title');
            that.css({'color': '#333'});
            row.find('.card_details_item').css({'color': '#333'});
            card_details_title.css({'color': '#333'});
        }
        // function convertStringToNumber (string)
        // {
        //     return parseFloat(parseFloat(string).toFixed(2));
        // }
        // convertStringToNumber('1350.00');
        $(document).on('keyup', ':input', function () {
            let update_button = $('#update_button');
            let hand_labour = makeToFixedNumber($('#hand_labour').val() || 0);
            let new_parts = makeToFixedNumber($('#new_parts').val() || 0);
            let used_parts = makeToFixedNumber($('#used_parts').val() || 0);
            let total_vat = makeToFixedNumber($('#total_vat').val());

            let sum_card_amounts = hand_labour + new_parts + used_parts + total_vat;
            sum_card_amounts = makeToFixedNumber(sum_card_amounts);

            let total_imports_area = $('#total_imports_area');

            let total_imports_amounts = makeToFixedNumber(total_imports_area.text());

            // console.log('hand_labour = ' + typeof hand_labour + ' - ' + hand_labour);
            // console.log('new_parts = ' + typeof new_parts + ' - ' + new_parts);
            // console.log('used_parts = ' + typeof used_parts + ' - ' + used_parts);
            // console.log('total_vat = ' + typeof total_vat + ' - ' + total_vat);
            // console.log('sum_card_amounts = ' + typeof sum_card_amounts + ' - ' + sum_card_amounts);
            // console.log('total_imports_amounts = ' + typeof total_imports_amounts + ' - ' + total_imports_amounts);


            if (sum_card_amounts === total_imports_amounts)
            {
                total_imports_area.removeClass('bg-danger').addClass('bg-success');
                update_button.css({'display': 'inline-block'});
                $('#card_details_validation_error').removeClass('hasError').css('display','none').text();
            }
            else
            {
                if (total_imports_amounts > 0)
                {
                    // if (sum_card_amounts < $('#total_vat').val() || sum_card_amounts > total_imports_amounts)
                    // {
                    //     console.log(sum_card_amounts + ' - ' + total_imports_amounts + ' - ' + $('#total_vat').val());
                    //     console.log(typeof sum_card_amounts+ ' - ' + typeof total_imports_amounts + ' - ' + typeof $('#total_vat').val());
                    //     $('#card_details_validation_error').addClass('hasError').css({'display': 'inline', 'font-size': 'small', 'font-style': 'italic', 'margin-bottom': '5px', 'font-weight': '700'}).text('برجاء ادخال اجمالى مبالغ تفاصيل الكارت بشكل صحيح بحيث يكون الاجمالى = ' + total_imports_amounts);
                    // }
                    if (sum_card_amounts !== total_imports_amounts)
                    {
                        let calc = parseFloat(total_imports_amounts - sum_card_amounts).toFixed(2);
                        $('#card_details_validation_error').addClass('hasError').css({'display': 'inline', 'font-size': 'small', 'font-style': 'italic', 'margin-bottom': '5px', 'font-weight': '700'}).text('اجمالى المبلغ الذى ادخلته ' + sum_card_amounts + ' مع الضريبة لا يساوى اجمالى مبلغ الفاتورة المقدر ب ' + total_imports_amounts + ' متبقى ' + calc);
                    }
                }
                total_imports_area.removeClass('bg-success').addClass('bg-danger');
                update_button.css({'display': 'none'});
            }

        })

    </script>
@endpush
