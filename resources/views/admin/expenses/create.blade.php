@php
$pageType = __('trans.create');
$pageItem = __('trans.expenses')

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
                    <h3 class="text-center"><i class="fa fa-user-secret"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.expenses.store', 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('amount', __('trans.amount'), ['class' => 'control-label']) !!}
                            {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('expenses_type_id', __('trans.expenses type'), ['class' => 'control-label']) !!}
                            {!! Form::select('expenses_type_id', [''=>'-- حدد نوع المصروف --'] + $expensesTypes , null , ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            @php($branch_id = auth()->user()->branch -> id?? '')
                            {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                            @if (array_key_exists($branch_id, $branches))
                                {!! Form::select('branch_id', [auth()->user()->branch -> id => auth()->user()->branch -> name] ,null , ['class' => 'form-control branch_id']) !!}
                            @else
                                {!! Form::select('branch_id', [''=>'-- اختر الفرع --'] + $branches ,null , ['class' => 'form-control branch_id']) !!}
                            @endif
                            {{--                            {!! Form::select('branch_id', [''=>'-- اختر الفرع --'] + $branches ,array_key_exists($branch_id, $branches)?$branch_id:'' , ['class' => 'form-control branch_id', array_key_exists($branch_id, $branches)?'disabled':'']) !!}--}}
                        </div>
                        <div class="form-group">
                            {!! Form::label('expenses_date', __('trans.expenses date'), ['class' => 'control-label']) !!}
                            {!! Form::text('expenses_date', null, ['class' => 'form-control',  'data-date-format' => 'yyyy-mm-dd']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('notes', __('trans.notes'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
                        </div>
                        <div>
                            <label for=""><b>طريقة الدفع</b></label>
                            <div class="form-group">
                                <input type="radio" name="payment_method" id="cash" value="كاش" checked>
                                <label for="cash">كاش</label>

                                <input type="radio" name="payment_method" id="bank_transfer" value="تحويل بنكى">
                                <label for="bank_transfer">تحويل بنكى</label>
                            </div>

                            <div class="form-group">
                                <input type="radio" name="payment_method" id="network" value="شبكة">
                                <label for="network">شبكة</label>

                                <input type="radio" name="payment_method" id="stc_pay" value="STC-Pay">
                                <label for="stc_pay">STC-Pay</label>
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
        $("#expenses_date").datepicker().datepicker("setDate", new Date()); // set datepicker
    </script>
@endpush
