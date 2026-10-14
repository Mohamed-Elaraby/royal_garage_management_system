@php
    $pageType = __('trans.create');
    $pageItem = __('trans.sale order return')
@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div>
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
                        <div class="card-header">
                            <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem }}</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['route' => 'admin.saleOrderReturns.store', 'method' => 'post', 'id' => 'sale_order_returns']) !!}
{{--                            {!! Form::hidden('invoice_date', $saleOrder -> invoice_date) !!}--}}
                            {!! Form::hidden('branch_id', $saleOrder -> branch_id) !!}
{{--                            {!! Form::hidden('client_id', $saleOrder -> client_id, ['id' => 'client_id']) !!}--}}
{{--                            {!! Form::hidden('check_id', $saleOrder -> check_id) !!}--}}
                            {!! Form::hidden('sale_order_id', $saleOrder -> id) !!}
                            <div style="margin: 0; padding: 0" class="col-xs-12 col-custom-style">
                                <div class="sale_order_return_products" style="width: 100%; margin: 0 auto">
                                    <table style="width: 100%" class="table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ __('trans.product code') }}</th>
                                            <th>{{ __('trans.product name') }}</th>
                                            <th>{{ __('trans.quantity') }}</th>
                                            <th>{{ __('trans.selling price') }}</th>
                                            <th>{{ __('trans.amount taxable') }}</th>
                                            <th>{{ __('trans.discount') }}</th>
                                            
                                            <th style="display:none">{{ __('trans.tax amount') }}</th>
                                            <th>الإجمالي بعد الخصم</th>
                                            <th>رد المبلغ الي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($saleOrderProducts -> sortByDesc('id') as $key => $product)
                                            <tr>
                                                <td>
                                                    @if ($product -> item_quantity > 0)
                                                        <input data-id="{{ $key }}" type="checkbox" name="product_data[{{ $key }}][checked]">
                                                    @endif
                                                </td>
                                                <td><input id="item_code_{{ $key }}" name="product_data[{{ $key }}][item_code]" type="text" class="form-control item_code" value="{{ $product -> item_code }}" readonly></td>
                                                <td><input id="item_name_{{ $key }}" name="product_data[{{ $key }}][item_name]" type="text" class="form-control item_name" value="{{ $product -> item_name }}" readonly title="{{ $product -> item_name }}"></td>
                                                <td><input id="item_quantity_{{ $key }}" name="product_data[{{ $key }}][item_quantity]" type="number" class="form-control item_quantity onlyUsedForValidation" value="{{ $product -> item_quantity }}" autocomplete="off" max="{{ $product -> item_quantity }}" min="0"><span class="item_quantity_error" style="color: red; display: none"></span></td>
                                                <td><input id="item_price_{{ $key }}" name="product_data[{{ $key }}][item_price]" type="text" class="form-control item_price momo onlyUsedForValidation" value="{{ $product -> item_price }}" autocomplete="off" readonly><span class="item_price_error" style="color: red; display: none"></span></td>
                                                <td><input id="item_amount_taxable_{{ $key }}" name="product_data[{{ $key }}][item_amount_taxable]" type="text" class="form-control item_amount_taxable" value="{{ $product -> item_amount_taxable }}" readonly></td>
                                                <td>
                                                    <div class="input-group my-group">
                                                        <select readonly id="discount_type_{{ $key }}" class="form-control discount_type" value="{{ $product -> item_discount_type }}" name="product_data[{{ $key }}][item_discount_type]">
                                                            <option value="{{ $product -> item_discount_type }}">{{ $product -> item_discount_type == 0 ? 'دولار' : '%' }}</option>
                                                        </select>
                                                        <input id="item_discount_{{ $key }}" name="product_data[{{ $key }}][item_discount]" type="text" class="form-control item_discount" value="{{ $product -> item_discount }}" autocomplete="off" readonly>
                                                    </div>
                                                </td>
                                                <input  type="hidden" name="product_data[{{ $key }}][item_discount_amount]" class="item_discount_amount" value="{{ $product -> item_discount_amount }}">
                                                <input  type="hidden" name="product_data[{{ $key }}][item_sub_total_after_discount]" class="item_sub_total_after_discount" value="{{ $product -> item_sub_total_after_discount }}">
                                                <td style="display:none"><input id="item_tax_amount_{{ $key }}" name="product_data[{{ $key }}][item_tax_amount]" type="text" class="form-control item_tax_amount" value="{{ $product -> item_tax_amount }}" readonly></td>
                                                <td><input id="item_sub_total_{{ $key }}" name="product_data[{{ $key }}][item_sub_total]" type="text" class="form-control item_sub_total" value="{{ $product -> item_sub_total }}" readonly></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- end col 8 -->
                            <div class="col-xs-12 col-custom-style">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label">الاجمالى قبل الخصم</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total" class="form-control total" id="total">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-sm-4 col-form-label">مجموع الخصومات</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="discount" class="form-control discount" id="discount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="taxable_amount" class="col-sm-4 col-form-label">الاجمالى بعد الخصم</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="taxable_amount" class="form-control taxable_amount" id="taxable_amount">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="total_vat" class="col-sm-4 col-form-label">مجموع ضريبة القيمة المضافة</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_vat" class="form-control total_vat" id="total_vat">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_return_items" class="col-sm-4 col-form-label">اجمالى الاصناف المردودة</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_return_items" class="form-control total_return_items" id="total_return_items">
                                    </div>
{{--                                    <span id="rounding_amount" class="btn btn-success btn-sm">تقريب المبلغ</span>--}}
                                </div>
                                <div>
                                    <label for="" class="text-center"><h3>تفاصيل الكارت</h3></label>
                                    <div class="form-group row">
                                        <label for="hand_labour" class="col-sm-4 col-form-label">تحديد مبلغ اجور اليد</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="hand_labour" class="form-control card_details hand_labour" id="hand_labour">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="new_parts" class="col-sm-4 col-form-label">تحديد مبلغ القطع الجديدة</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="new_parts" class="form-control card_details new_parts" id="new_parts">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="used_parts" class="col-sm-4 col-form-label">تحديد مبلغ القطع المستعملة</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="used_parts" class="form-control card_details used_parts" id="used_parts">
                                        </div>
                                    </div>

                                    <div class="form-group row" style="display:none">
                                        <label for="card_details_tax" class="col-sm-4 col-form-label">تحديد مبلغ الضريبة</label>
                                        <div class="col-sm-2">
                                            <input readonly type="text" name="card_details_tax" class="form-control card_details card_details_tax" id="card_details_tax">
                                            <input type="hidden" name="amount_paid" class="form-control amount_paid" id="amount_paid">
                                            <input type="hidden" name="amount_paid_bank" class="form-control amount_paid_bank" id="amount_paid_bank">

                                        </div>
                                    </div>
                                    <span id="card_details_error" style="color: red; display: none"></span>

                                </div>
                                <div class="col-xs-12 col-custom-style">
                                    <div class="form-group">
                                        <lable><b>ملاحظات</b></lable>
                                        <textarea name="notes" id="notes" rows="5" class="form-control" placeholder="ملاحظات"></textarea>
                                    </div>
                                </div> <!-- end col 12 -->
                            </div> <!-- end col 12 -->
                            <div class="col-xs-12 col-custom-style">
                                <div  style="width: 25%; margin: 0 auto">
                                    {!! Form::submit($pageType, ['class' => 'form-control btn btn-primary', 'disabled']) !!}
                                </div>
                            </div> <!-- end col 12 -->
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/createPurchase&SalePages.css') }}">

    <style>
        .info th, .info td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .my-group .form-control{
            width:50%;
        }
    </style>
@endpush
@push('scripts')

    <!-- Calculator Script -->
    <script src="{{ asset('js/orderReturnsScript.js') }}"></script>
    <!-- Jquery Validation -->
    <script src="{{ asset('js/jquryValidation/jquery.form.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/additional-methods.min.js') }}"></script>

    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <!-- RTL Files -->
        <script src="{{ asset('js/jquryValidation/messages_ar.min.js') }}"></script>
    @endif

    <script>

        // JQuery Validation
        $('form').on('submit', function (e) {
            $('select.return_amount_in').each(function () {$(this).rules('add', {required: true});});

            e.preventDefault();
        });

        // JQuery Validation
        $("#sale_order_returns").validate({
            // rules: {
            //     return_amount_in: "required"
            // },
            submitHandler: function(form) {
                form.submit();
            }
        });

        // on select row make select box to choose return amount in money safe or bank
        $(document).on('change', 'input[type="checkbox"]', function () {
            let that = $(this);
            let rowNumber = that.data('id');
            let rowSelected = that.closest('tr');
            // at least one checkbox is checked enabled submit button
            $('input[type="checkbox"]:checked').length > 0 ?  $('input[type="submit"]').prop('disabled', false) :  $('input[type="submit"]').prop('disabled', true);
            // if  row checked create input return amount in money safe or bank
            if(that.is(':checked'))
            {
                let content = '<td class="return_amount_in_row">' +
                    '<select name="product_data['+rowNumber+'][return_amount_in]" class="form-control return_amount_in">' +
                    '<option value=""></option>' +
                    '<option value="money_safe">الخزنة</option>' +
                    '<option value="bank">البنك</option>' +
                    '<option value="client_balance">رصيد العميل</option>' +
                    '</select>' +
                    '</td>';
                rowSelected.append(content);
            }else { // remove input return amount in money safe or bank
                let return_amount_in_count = rowSelected.find('.return_amount_in_row').length;
                if(return_amount_in_count > 0)
                {
                    rowSelected.find('.return_amount_in_row').remove();
                }

            }

            // console.log(rowNumber)
        })
    </script>

    <!-- Prevent Double Submission Form -->
    <script src="{{ asset('js/preventDoubleSubmissionForm.js') }}"></script>

@endpush


