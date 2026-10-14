@php
$pageType = __('trans.create');
$pageItem = __('trans.supplier payment')

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
                    <h3 class="text-center"><i class="fa fa-briefcase"></i> {{ $pageType .' '. $pageItem }} [ {{ $supplier -> name }} ]</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.supplierPayments.store', 'method' => 'post']) !!}
                    {!! Form::hidden('supplier_id' , request()->supplier_id) !!}
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
                    @php($branch_id = auth()->user()->branch -> id?? '')

                    <div class="form-group">
                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('branch_id', [''=>'-- اختر الفرع --'] + $branches ,array_key_exists($branch_id, $branches)?$branch_id:'' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('payment_date', __('trans.payment date'), ['class' => 'control-label']) !!}
                        {!! Form::text('payment_date', null, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', __('trans.notes'), ['class' => 'control-label']) !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}

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
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

@endpush
@push('scripts')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $("#payment_date").datepicker().datepicker("setDate", new Date()); // set datepicker

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
@endpush
