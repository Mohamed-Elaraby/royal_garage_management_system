@php
    $pageType = __('trans.create');
    $pageItem = __('trans.advance')

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
                <div class="error_messages text-center">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('delete'))
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle text-yellow"></i> {{ session('delete') }} <i class="fa fa-exclamation-triangle text-yellow"></i>
                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th>{{ __('trans.required advance') }}</th>
                                    <td>{{ session('details')['advance_amount']. ' دولار ' }}</td>
                                </tr>
                                <tr>
                                    <th>{{  __('trans.salary') }}</th>
                                    <td>{{ session('details')['employee_salary']. ' دولار ' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('trans.total rewards') }}</th>
                                    <td>{{ session('details')['rewards_during_the_month']. ' دولار ' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('trans.total advances') }}</th>
                                    <td>{{ session('details')['advances_during_the_month']. ' دولار ' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('trans.remaining amount') }}</th>
                                    <td>{{ session('details')['total_employee_receives']. ' دولار ' }}</td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="card-header">
                    <h3 class="text-center"><i class="fa fa-child"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'employee.advances.store', 'method' => 'post', 'id' => 'form_target']) !!}
                    {!! Form::hidden('employee_id', $employee -> id) !!}
                    <div class="form-group">
                        {!! Form::label('amount', __('trans.amount'), ['class' => 'control-label']) !!}
                        {!! Form::text('amount', old('amount'), ['class' => 'form-control']) !!}
                        <span style="display: none" id="amount_error"></span>
                    </div>
                    <div id="type_group">
                        <div class="form-group">
                            {!! Form::label('type', __('trans.type'), ['class' => 'control-label']) !!}
                            {!! Form::select('type', ['' => '__ اختر نوع السلفة __','تخصم مباشرة' => __('trans.deducted directly'), 'مجدولة' => __('trans.schedule')] , null , ['class' => 'form-control', 'id' => 'type']) !!}
                        </div>
                        <div id="payment_schedule_group"></div>
                        <div id="payment_method"></div>
                        <div id="generate_content"></div>
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
@push('links')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

@endpush
@push('scripts')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>

        let Generate_content = $('#generate_content');

        function disabledFormSubmit ()
        {
            $('input[type="submit"]').prop('disabled', true);
        }
        function enabledFormSubmit ()
        {
            $('input[type="submit"]').prop('disabled', false);
        }

        $('select, input').on("change keyup", function() {
                // checkFormErrors();
                let Generate_content = $('#generate_content');
                Generate_content.empty();
                let advanceType = $('#type').val();

                let advanceAmountValue = $('#amount').val();
                let errorStyle = {
                    'display': 'block',
                    'color': 'red',
                    'font-style': 'italic',
                    'font-size': 'small'
                };

                if(advanceType === 'تخصم مباشرة')
                {
                    if(advanceAmountValue === '' || advanceAmountValue == 0)
                    {
                        $('#amount_error').addClass('error').css(errorStyle).text('رجاء ادخال مبلغ السلفة');
                        disabledFormSubmit();
                    }else
                    {
                        $('#amount_error').removeClass('error').css('display', 'none');
                        generatePaymentMethod ();
                        enabledFormSubmit();
                    }
                }
                else if(advanceType === 'مجدولة')
                {
                    // let advanceAmountValue = $('#amount').val();
                    if(advanceAmountValue === '' || advanceAmountValue == 0)
                    {
                        $('#amount_error').addClass('error').css(errorStyle).text('رجاء ادخال مبلغ السلفة');
                        disabledFormSubmit();
                    }else
                    {
                        $('#amount_error').removeClass('error').css('display', 'none');
                        enabledFormSubmit();
                        generate_number_of_schedule_input();
                    }

                    $('#number_of_schedule').on("change keyup", function() {
                        let number_of_schedule = $(this).val();
                        if(number_of_schedule === '' || number_of_schedule == 0)
                        {
                            $('#number_of_schedule_error').addClass('error').css(errorStyle).text('رجاء ادخال عدد الدفعات');
                            $('#payment_method_group, #single_payment_amount').remove();
                            disabledFormSubmit();
                        }
                        else
                        {
                            $('#number_of_schedule_error').removeClass('error').css('display', 'none');
                            $('#payment_method_group, #single_payment_amount').remove();
                            generate_payment_schedule();
                            generate_single_amount_input();
                            enabledFormSubmit();
                        }
                    });



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
            Generate_content.append(content);

        }


        function generate_number_of_schedule_input ()
        {
            let content =
                '    <div id="number_of_schedule_group">\n' +
                '        <div class="form-group">\n' +
                '            <label for="number_of_schedule" class="form-label">'+'{{ __('trans.number of schedule') }}'+'</label>\n' +
                '            <input type="number" min="1" name="number_of_schedule" class="form-control number_of_schedule" id="number_of_schedule">\n' +
                '            <span style="display: none" id="number_of_schedule_error"></span>\n' +
                '        </div>\n' +
                '    </div>';
            Generate_content.append(content);
        }


        function generate_payment_schedule ()
        {
            let html =
                '<div id="payment_method_group">\n' +
                '<div class="form-group" id="payment_method">\n' +
                '    <label>طريقة سداد المبلغ</label>\n' +
                '    <br>\n' +
                '    <label for="equally_amount" class="radio-inline">\n' +
                '        <input type="radio" name="pay_method" value="equally_amount" id="equally_amount">تقسيم المبلغ بالتساوي على عدد الدفعات\n' +
                '    </label>\n' +
                '    <label for="custom_amount" class="radio-inline">\n' +
                '        <input type="radio" name="pay_method" value="custom_amount" id="custom_amount">مخصص\n' +
                '    </label>\n' +
                '</div>\n' +
                '</div>';
            Generate_content.append(html);
        }

        function generate_single_amount_input ()
        {
            $('input[type="radio"]').on("change", function() {
                let advanceAmount = parseFloat($('#amount').val()).toFixed(2);
                let numberOfSchedule = $('#number_of_schedule').val();
                let single_amount = 0;
                let pay_type = $(this).val();
                let single_amount_input_status = '';
                let errorStyle = {
                    'display': 'block',
                    'color': 'red',
                    'font-style': 'italic',
                    'font-size': 'small'
                };

                if(pay_type === 'equally_amount')
                {
                    single_amount = (advanceAmount / numberOfSchedule);
                    single_amount_input_status = 'readonly';
                }else
                {
                    // must be all amounts not larger than total advance amount
                    $(document).on('keyup', '.single_payment_amount', function () {
                        let advanceAmount = parseFloat($('#amount').val());
                        let sum = 0;
                        $('.single_payment_amount').each(function() {
                            sum += Number($(this).val());
                        });
                        if(sum !== advanceAmount)
                        {
                            $('#single_payment_amount_error').css(errorStyle).text('اجمالى المبالغ التى ادخلتها ' + sum.toFixed(2) + ' برجاء تعديل المبلغ حتى يتساوى مع المبلغ الاجمالى للسلفة المقدر ب ' + advanceAmount);
                            disabledFormSubmit();
                        }else
                        {
                            $('#single_payment_amount_error').css('display', 'none');
                            enabledFormSubmit();
                        }
                    })



                }
                let single_amount_input = '';
                for (let i = 1; i <= numberOfSchedule; i++) {
                    single_amount_input += '<div class="col-md-4"><input type="text" name="single_amount[]" '+single_amount_input_status+' value="'+Math.round(single_amount)+'" class="form-control single_payment_amount" id="single_payment_amount"></div>';
                }

                let content = '    <div id="single_payment_amount_group">\n' +
                    '        <div class="form-group">\n' +
                    '            <div class="row">'+single_amount_input+'</div><br>\n' +
                    '            <label class="control-label"><b>طريقة الدفع</b></label>\n' +
                    '            <div class="form-group">\n' +
                    '                <input type="radio" name="payment_method" id="cash" value="cash" checked>\n' +
                    '                <label for="cash">كاش</label>\n' +
                    '                <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">\n' +
                    '                <label for="bank_transfer">تحويل بنكى</label>\n' +
                    '            </div>\n' +
                    '            <span style="display: none" id="single_payment_amount_error"></span>\n' +
                    '        </div>\n' +
                    '    </div>';
                $('#single_payment_amount_group').remove();
                Generate_content.append(content);
                // generatePaymentMethod();
            });
        }
    </script>
@endpush
