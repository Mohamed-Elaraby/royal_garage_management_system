@php
$pageType = __('trans.create');
$pageItem = __('trans.operation on the money safe')

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
                    <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem . ' [ ' .$branch->name . ' ] ' }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.moneySafe.store', 'method' => 'post']) !!}
                    {!! Form::hidden('branch_id', request()->branch_id) !!}
                        <div class="form-group">
                            {!! Form::label('amount_paid', __('trans.amount'), ['class' => 'control-label']) !!}
                            {!! Form::text('amount_paid', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('processType', __('trans.type'), ['class' => 'control-label']) !!}
                            {!! Form::select('processType', ['' => '--  اختر العملية  --', 0 => 'سحب', 1 => 'ايداع'] , null , ['class' => 'form-control']) !!}
                        </div>
{{--                        <div id="generate_content"></div>--}}
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
@push('scripts')


    {{--<script>
        let Generate_content = $('#generate_content');
        $('#processType').on('change', function(){
            let processTypeValue = parseInt($(this).val());
            if (processTypeValue === 1)
            {
                generatePaymentMethod ();
            }else
            {
                Generate_content.empty();
            }

        });

        function generatePaymentMethod ()
        {

            Generate_content.empty();
            let content =
                '    <label><b>طريقة الدفع</b></label>\n' +
                '    <div class="form-group">\n' +
                '        <input type="radio" name="payment_method" id="cash" value="cash" checked>\n' +
                '        <label for="cash">كاش</label>\n' +
                '\n' +
                '        <input type="radio" name="payment_method" id="network" value="network">\n' +
                '        <label for="network">شبكة</label>\n' +
                '    </div>';
            Generate_content.append(content);

        }
    </script>--}}

@endpush
