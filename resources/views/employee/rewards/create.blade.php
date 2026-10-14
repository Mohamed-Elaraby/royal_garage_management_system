@php
    $pageType = __('trans.create');
    $pageItem = __('trans.reward')

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
                    <h3 class="text-center"><i class="fa fa-child"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'employee.rewards.store', 'method' => 'post']) !!}
                    {!! Form::hidden('employee_id', $employee -> id) !!}
                    <div class="form-group">
                        {!! Form::label('amount', __('trans.amount'), ['class' => 'control-label']) !!}
                        {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                    </div>
                    <div id="type_group">
                        <div class="form-group">
                            {!! Form::label('type', __('trans.type'), ['class' => 'control-label']) !!}
                            {!! Form::select('type', ['' => '__ اختر حالة المكافاة __','تضاف الى الراتب' => 'تضاف الى الراتب', 'يحصل عليها العامل فورا' => 'يحصل عليها العامل فورا'] , null , ['class' => 'form-control', 'id' => 'type']) !!}
                        </div>
                        <div id="payment_method"></div>
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
@endsection
@push('scripts')
    <script>

        $('#type').on("change", function() {
            let rewardType = $(this).val();

            if(rewardType === 'يحصل عليها العامل فورا')
            {
                generatePaymentMethod();
            }else
            {
                $('#payment_method').empty();
            }

        });

        function generatePaymentMethod ()
        {
            let content =
                '    <label><b>طريقة الدفع</b></label>\n' +
                '    <div class="form-group">\n' +
                '        <input type="radio" name="payment_method" id="cash" value="cash" checked>\n' +
                '        <label for="cash">كاش</label>\n' +
                '\n' +
                '        <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">\n' +
                '        <label for="bank_transfer">تحويل بنكى</label>\n' +
                '    </div>';
            $('#payment_method').prepend(content);

        }
    </script>
@endpush
