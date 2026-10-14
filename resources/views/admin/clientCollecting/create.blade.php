@php
$pageType = __('trans.create');
$pageItem = __('trans.client collecting')

@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-6 center-block" style="float: none">
            <div class="card card-success">
                <div class="error_messages">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-header">
                    <h3 class="text-center"><i class="fa fa-briefcase"></i> {{ $pageType .' '. $pageItem }}[ {{ $client -> name }} ]</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.clientCollecting.store', 'method' => 'post']) !!}
                    {!! Form::hidden('client_id' , request()->client_id) !!}

                    <div>
                        <label for=""><b>طريقة الدفع</b></label>
                        <div class="form-group">
                            <input class="payment_method" type="checkbox" name="payment_method" id="cash" value="كاش" checked>
                            <label for="cash">كاش</label>
                        </div>

                        <div class="form-group">
                            <input class="payment_method" type="checkbox" name="payment_method_bank" id="bank_transfer" value="تحويل بنكى">
                            <label for="bank_transfer">تحويل بنكى</label>

                            <input class="payment_method" type="checkbox" name="payment_method_bank" id="network" value="شبكة">
                            <label for="network">شبكة</label>

                            <input class="payment_method" type="checkbox" name="payment_method_bank" id="stc_pay" value="STC-Pay">
                            <label for="stc_pay">STC-Pay</label>
                        </div>
                        <span id="payment_method_error" style="color: red; display: none"></span>
                    </div>
                    <div id="amounts_group_field">
                        <div id="safe_amount_field" class="form-group row">
                            <label for="amount_paid" class="col-sm-4 col-form-label">المبلغ المدفوع فى الخزنة</label>
                            <div class="col-sm-8">
                                <input type="text" name="amount_paid" class="form-control amount_paid" id="amount_paid" autocomplete="off">
                                </div>
                            </div>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        {!! Form::label('amount_paid', __('trans.amount paid in money safe'), ['class' => 'control-label']) !!}--}}
{{--                        {!! Form::text('amount_paid', null, ['class' => 'form-control']) !!}--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        {!! Form::label('amount_paid_bank', __('trans.amount paid in bank'), ['class' => 'control-label']) !!}--}}
{{--                        {!! Form::text('amount_paid_bank', null, ['class' => 'form-control']) !!}--}}
{{--                    </div>--}}
                    @php($branch_id = auth()->user()->branch -> id?? '')

                    <div class="form-group">
                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('branch_id', [''=>'-- اختر الفرع --'] + $branches ,array_key_exists($branch_id, $branches)?$branch_id:'' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('collecting_date', __('trans.collecting date'), ['class' => 'control-label']) !!}
                        {!! Form::text('collecting_date', null, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', __('trans.notes'), ['class' => 'control-label']) !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}

                    </div>

                    <div>
                        <label for="" class="text-center"><h3>تفاصيل الكارت</h3></label>
                        <div class="form-group row">
                            <label for="hand_labour" class="col-sm-4 col-form-label">تحديد مبلغ اجور اليد</label>
                            <div class="col-sm-8">
                                <input type="text" name="hand_labour" class="form-control card_details hand_labour" id="hand_labour">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_parts" class="col-sm-4 col-form-label">تحديد مبلغ القطع الجديدة</label>
                            <div class="col-sm-8">
                                <input type="text" name="new_parts" class="form-control card_details new_parts" id="new_parts">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="used_parts" class="col-sm-4 col-form-label">تحديد مبلغ القطع المستعملة</label>
                            <div class="col-sm-8">
                                <input type="text" name="used_parts" class="form-control card_details used_parts" id="used_parts">
                            </div>
                        </div>

                        <div class="form-group row" style="display:none">
                            <label for="card_details_tax" class="col-sm-4 col-form-label">تحديد مبلغ الضريبة</label>
                            <div class="col-sm-8">
                                <input readonly type="text" name="card_details_tax" class="form-control card_details card_details_tax" id="card_details_tax">
                            </div>
                        </div>
                        <span id="card_details_error" style="color: red; display: none"></span>

                    </div>
                </div>
                    <div class="form-group">
                            {!! Form::submit($pageType, ['class' => 'form-control btn btn-success']) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')

{{--    <meta http-equiv='cache-control' content='no-cache'>--}}
{{--    <meta http-equiv='expires' content='0'>--}}
{{--    <meta http-equiv='pragma' content='no-cache'>--}}


    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

@endpush
@push('scripts')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $("#collecting_date").datepicker().datepicker("setDate", new Date()); // set datepicker

        $('input[name="payment_method"]').on('change', function () {
            // checkPaymentMethodExist();
            if ($(this).is(':checked'))
            {
                if (!$('#safe_amount_field').length)
                {
                    let content =
                        '<div id="safe_amount_field" class="form-group row">\n' +
                        '<label for="amount_paid" class="col-sm-4 col-form-label">المبلغ المدفوع فى الخزنة</label>\n' +
                        '<div class="col-sm-8">\n' +
                        '<input type="text" name="amount_paid" class="form-control amount_paid" id="amount_paid" autocomplete="off">\n' +
                        '</div>\n' +
                        '</div>';
                    $('#amounts_group_field').prepend(content);
                }

            }else{
                if ($('#safe_amount_field').length)
                {
                    $('#safe_amount_field').remove();
                }
            }
        })

        // on change payment method create new field to pay into bank
        $('input[name="payment_method_bank"]').on('change', function () {
            $(this).siblings('input[type="checkbox"]').not(this).prop('checked', false);
            // checkPaymentMethodExist();
            if ($('input[name="payment_method_bank"]:checked').length > 0)
            {
                if (!$('#bank_field').length)
                {
                    let content =
                        '<div id="bank_field" class="form-group row">\n' +
                        '<label for="amount_paid_bank" class="col-sm-4 col-form-label">المبلغ المدفوع فى البنك</label>\n' +
                        '<div class="col-sm-8">\n' +
                        '<input type="text" name="amount_paid_bank"  class="form-control amount_paid_bank" id="amount_paid_bank" autocomplete="off">\n' +
                        '</div>\n'+
                        '</div>';
                    $('#amounts_group_field').append(content);
                }

            }else{
                if ($('#bank_field').length)
                {
                    $('#bank_field').remove();
                }
            }
        })
    </script>
    <script>
        $(document).on('keyup change', ':input', function () {
            putHandLabourAndPartsAmount();
        });

        function makeToFixedNumber(num) {
            // return Math.trunc(num*100)/100;
            return Math.round(num * 100) / 100;
        }

        function putHandLabourAndPartsAmount() {

            let amount_paid = parseFloat($('#amount_paid').val())|| 0;
            let amount_paid_bank = parseFloat($('#amount_paid_bank').val())|| 0;

            let total_amounts_paid  = parseFloat(amount_paid + amount_paid_bank);
            console.log('total_amounts_paid = ' + total_amounts_paid, typeof total_amounts_paid);

            let hand_labour = parseFloat($('#hand_labour').val())|| 0;
            let new_parts = parseFloat($('#new_parts').val())|| 0;
            let used_parts = parseFloat($('#used_parts').val())|| 0;


            let calculate_tax_amount = 0;
            let card_details_tax = $('#card_details_tax');
            let total_card_details_amount = makeToFixedNumber(hand_labour + new_parts + used_parts + calculate_tax_amount);

            card_details_tax.val(calculate_tax_amount);
            console.log('total_card_details_amount = ' + total_card_details_amount, typeof total_card_details_amount);
            console.log('calculate_tax_amount = ' + calculate_tax_amount, typeof calculate_tax_amount);




            // let total_vat = parseFloat(_total_vat_element.val())|| 0;
            // let total_amount_due = parseFloat($('#total_amount_due').val());
            // let total_card_details_amount = hand_labour + new_parts + used_parts + total_vat;

            if (total_amounts_paid > 0)
            {
                if (total_card_details_amount < 1 || total_card_details_amount > total_amounts_paid)
                {
                    $('#card_details_error').addClass('hasError').css({'display': 'inline', 'font-size': 'small', 'font-style': 'italic', 'margin-bottom': '5px', 'font-weight': '700'}).text('برجاء ادخال اجمالى مبالغ تفاصيل الكارت بشكل صحيح بحيث يكون الاجمالى = ' + total_amounts_paid);
                }
                else if (total_card_details_amount !== total_amounts_paid)
                {
                    let calc = parseFloat(total_amounts_paid - total_card_details_amount).toFixed(2);
                    $('#card_details_error').addClass('hasError').css({'display': 'inline', 'font-size': 'small', 'font-style': 'italic', 'margin-bottom': '5px', 'font-weight': '700'}).text('اجمالى المبلغ الذى ادخلته ' + total_card_details_amount + ' مع الضريبة لا يساوى اجمالى مبلغ السند المقدر ب ' + total_amounts_paid + ' متبقى ' + calc);
                }
                else
                {
                    $('#card_details_error').removeClass('hasError').css('display','none').text();
                }
            }else
            {
                $('#hand_labour, #new_parts, #used_parts').val('');
                $('#card_details_error').removeClass('hasError').css('display','none').text();
            }
        }
    </script>

    <script>
        // if page contain any class hasError stop submitted form
        $('input[type="submit"]').on('click', function (e) {
            if ($('.hasError').length > 0)
            {
                e.preventDefault();
            }
        });
    </script>
@endpush
