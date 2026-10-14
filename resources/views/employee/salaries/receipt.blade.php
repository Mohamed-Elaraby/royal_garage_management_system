@php
    $pageType = __('trans.salary details');
    $pageItem = __('trans.salary receipt');
    $previous_month = \Carbon\Carbon::now()->subMonth()->format('m');
    $current_month = \Carbon\Carbon::now()->month;
    $previous_year = \Carbon\Carbon::now()->subYear()->format('Y');
    $current_year = \Carbon\Carbon::now()->year;
@endphp
@extends('admin.layouts.app')

@section('title', $pageType)

@section('content')

    <div class="row">
        <div class="col-xs-6 center-block" style="float: none">
            <div class="alert alert-danger text-center"
                 style="display: none;
               position: fixed;
                top: 10%;
                left: 50%;
                transform: translate(-50%, -50%);
                 width: 50%;
                    z-index: 1000"
                 id="check_salary_value">
            </div>
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
                @if ($content_allowed)
                    <div class="content_area">
                        <div class="card-header">
                            @php($previous_month = \Carbon\Carbon::now()->subMonth()->format('m'))
                            <h3 class="text-center"><i class="fa fa-child"></i> تفاصيل راتب شهر {{ $previous_month }} - {{ $current_month == 1 ? $previous_year : $current_year }} [ {{ $employee -> name  }} ]</h3>
                        </div>
                        <div class="card-body">
{{--                            {!! Form::open(['route' => ['employee.salaries.employeeSignature', $employee->id], 'method' => 'get']) !!}--}}
                            {!! Form::open(['route' => ['employee.salaries.registerToEmployeeLog', $employee->id], 'method' => 'post']) !!}
                            {!! Form::hidden('employee_id', $employee->id) !!}
                            <div class="row">
                                <input type="hidden" name="salary_month" value="{{ $previous_month }}-{{ $current_month == 1 ? $previous_year : $current_year }}">
                                <div class="col-md-4">
                                    {!! Form::label('main', __('trans.main'), ['class' => 'control-label']) !!}
                                    {!! Form::text('main', $employee -> salary -> main, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('housing_allowance', __('trans.housing allowance'), ['class' => 'control-label']) !!}
                                    {!! Form::text('housing_allowance', $employee -> salary -> housing_allowance, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('transfer_allowance', __('trans.transfer allowance'), ['class' => 'control-label']) !!}
                                    {!! Form::text('transfer_allowance', $employee -> salary -> transfer_allowance, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('travel_allowance', __('trans.travel allowance'), ['class' => 'control-label']) !!}
                                    {!! Form::text('travel_allowance', $employee -> salary -> travel_allowance, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-3">
                                            {!! Form::label('other_allowance', __('trans.other allowance'), ['class' => 'control-label']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('other_allowance', __('trans.amount'), ['class' => 'control-label']) !!}
                                            {!! Form::text('other_allowance', $employee -> salary -> other_allowance, ['class' => 'form-control', 'readonly']) !!}
                                        </div>

                                        <div class="col-md-5">
                                            {!! Form::label('description_of_other_allowance', __('trans.details'), ['class' => 'control-label']) !!}
                                            {!! Form::text('description_of_other_allowance', $employee -> salary -> description_of_other_allowance, ['class' => 'form-control', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                @if ($employee -> salary -> end_service_allowance)
                                    <div class="col-md-4">
                                        {!! Form::label('end_service_allowance', __('trans.end service allowance'), ['class' => 'control-label']) !!}
                                        {!! Form::text('end_service_allowance', $employee -> salary -> end_service_allowance, ['class' => 'form-control', 'readonly']) !!}
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    {!! Form::label('total_salary', __('trans.total'), ['class' => 'control-label']) !!}
                                    {!! Form::text('total_salary', $total_salary, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                                <div class="clearfix"></div>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="text-center"> {{ __('trans.all advances') }} </h3>
                                        <table class="table table-bordered table-responsive bg-success">
                                            <thead>
                                            <th>{{ __('trans.date') }}</th>
                                            <th>{{ __('trans.amount') }}</th>
                                            </thead>
                                            <tbody>
                                            @foreach ($advance_list as $advance)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($advance -> updated_at)->format('d-m-Y') }}</td>
                                                    <td class="amount">{{ $advance -> amount }}</td>
{{--                                                    <td><input type="checkbox" name="select_normal_advance_amount[]" id="select_normal_advance_amount" class="select_normal_advance_amount" value="{{ $advance -> id }}"></td>--}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div class="col-md-12">
                                            {!! Form::label('total_advances', __('trans.total'), ['class' => 'control-label']) !!}
                                            {!! Form::text('total_advances', $totalAdvance, ['class' => 'form-control', 'readonly', 'id' => 'total_advances']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3 class="text-center"> {{ __('trans.scheduled advance') }} </h3>
                                        <table class="table table-bordered table-responsive bg-danger">
                                            <thead>
                                            {{--                                        <th>#</th>--}}
                                            <th>{{ __('trans.date') }}</th>
                                            <th>{{ __('trans.amount') }}</th>
                                            <th></th>
                                            </thead>
                                            <tbody>
                                            @foreach ($schedule_advance as $advance)
                                                <tr>
                                                    {{--                                                <td><input type="text" name="advance_id" class="advance_id" value="{{ $advance -> id }}"></td>--}}
                                                    <td>{{ \Carbon\Carbon::parse($advance -> updated_at)->format('d-m-Y') }}</td>
                                                    <td class="amount">{{ $advance -> amount }}</td>
                                                    <td><input type="checkbox" name="select_advance_amount[]" id="select_advance_amount" class="select_advance_amount" value="{{ $advance -> id }}"></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div class="col-md-12">
                                            {!! Form::label('scheduledAdvance', __('trans.total'), ['class' => 'control-label']) !!}
                                            {!! Form::text('scheduledAdvance', 0, ['class' => 'form-control', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-center"> {{--{{ __('trans.all rewards') }}--}} المكافات الفورية التى حصل عليها الموظف اثناء الشهر </h3>
                                <table class="table table-bordered table-responsive bg-success">
                                    <thead>
                                    <th>{{ __('trans.date') }}</th>
                                    <th>{{ __('trans.amount') }}</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($direct_rewards as $reward)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($reward -> updated_at)->format('d-m-Y') }}</td>
                                            <td>{{ $reward -> amount }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    {!! Form::label('total_rewards', __('trans.total'), ['class' => 'control-label']) !!}
                                    {!! Form::text('total_rewards', $totalRewards, ['class' => 'form-control', 'readonly', 'id'=> 'total_rewards']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="text-center"> {{--{{ __('trans.scheduled advance') }}--}}المكافات المضافة الى الراتب </h3>
                                <table class="table table-bordered table-responsive bg-danger">
                                    <thead>
                                    <th>{{ __('trans.date') }}</th>
                                    <th>{{ __('trans.amount') }}</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    @foreach ($to_salary_rewards as $rewardToSalary)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($rewardToSalary -> updated_at)->format('d-m-Y') }}</td>
                                            <td class="amount">{{ $rewardToSalary -> amount }}</td>
                                            <td><input type="checkbox" name="select_reward_amount[]" id="select_reward_amount" class="select_reward_amount" value="{{ $rewardToSalary -> id }}"></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    {!! Form::label('scheduledReward', __('trans.total'), ['class' => 'control-label']) !!}
                                    {!! Form::text('scheduledReward', 0, ['class' => 'form-control', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center"> {{--{{ __('trans.all rewards') }}--}} الخصومات التى حصل عليها الموظف اثناء الشهر </h3>
                                <table class="table table-bordered table-responsive bg-danger">
                                    <thead>
                                    <th>{{ __('trans.date') }}</th>
                                    <th>{{ __('trans.amount') }}</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    @foreach ($discounts_list as $discount)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($discount -> updated_at)->format('d-m-Y') }}</td>
                                            <td class="amount">{{ $discount -> amount }}</td>
                                            <td><input type="checkbox" name="select_discount_amount[]" id="select_discount_amount" class="select_discount_amount" value="{{ $discount -> id }}"></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    {!! Form::label('totalDiscount', __('trans.total'), ['class' => 'control-label']) !!}
                                    {!! Form::text('total_discounts', 0, ['class' => 'form-control totalDiscounts', 'readonly', 'id' => 'totalDiscounts']) !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-center"> {{--{{ __('trans.all rewards') }}--}} الاجازات مدفوعة الاجر </h3>
                                <table class="table table-bordered table-responsive bg-success">
                                    <thead>
                                    <th>{{ __('trans.start date of vacation') }}</th>
                                    <th>{{ __('trans.end date of vacation') }}</th>
                                    <th>{{ __('trans.total number of vacation days') }}</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($paid_vacations as $vacation)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($vacation -> start_vacation)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($vacation -> end_vacation)->format('d-m-Y') }}</td>
                                            <td>{{ $vacation -> total_days }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h3 class="text-center"> {{--{{ __('trans.scheduled advance') }}--}}الاجازات التى لم تخصم من الراتب </h3>
                                <table class="table table-bordered table-responsive bg-danger">
                                    <thead>
                                    <th>{{ __('trans.start date of vacation') }}</th>
                                    <th>{{ __('trans.end date of vacation') }}</th>
                                    <th>{{ __('trans.total number of vacation days') }}</th>
                                    <th>{{ __('trans.discount amount') }}</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    @foreach ($vacationsDeductedFromTheSalary as $vacation)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($vacation -> start_vacation)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($vacation -> end_vacation)->format('d-m-Y') }}</td>
                                            <td>{{ $vacation -> total_days }}</td>
                                            <td class="amount">{{ $vacation -> discount_amount }}</td>
                                            <td><input type="checkbox" name="select_vacations_deducted_amount[]" id="select_vacations_deducted_amount" class="select_vacations_deducted_amount" value="{{ $vacation -> id }}"></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    {!! Form::label('total_vacations', __('trans.total'), ['class' => 'control-label']) !!}
                                    {!! Form::text('total_vacations', 0, ['class' => 'form-control', 'readonly', 'id' => 'vacations_deducted']) !!}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-center"> الراتب المستحق للعامل خلال الشهر </h3>
                                {!! Form::text('final_salary', null, ['class' => 'form-control', 'readonly', 'id' => 'final_salary']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for=""><b>طريقة الدفع</b></label>
                                <div class="form-group">
                                    <input class="payment_method" type="radio" name="payment_method" id="cash" value="كاش" checked>
                                    <label for="cash">كاش</label>
                                </div>
                                <input class="payment_method" type="radio" name="payment_method" id="bank_transfer" value="تحويل بنكى">
                                <label for="bank_transfer">تحويل بنكى</label>
                            </div>
                            <span id="payment_method_error" style="color: red; display: none"></span>
                        </div>
                        <div style="margin-top: 20px" class="form-group">
                            {!! Form::submit('قبض', ['class' => 'form-control btn btn-success']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        <i class="fa fa-exclamation-triangle"></i>
                        الراتب غير متوفر قم بمراجعة الإدارة المالية
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('links')

@endpush
@push('scripts')
{{--    <!-- make total normal advance -->--}}
{{--    <script>--}}
{{--        let sumNormalAdvances = 0;--}}
{{--        let normalAdvance = $('#total_advances');--}}
{{--        $(document).on('change', '.select_normal_advance_amount', function() {--}}
{{--            let normal_advance_amount = parseFloat($(this).closest('tr').find('.amount').text());--}}
{{--            // let advance_ids = [];--}}
{{--            let totalNormalAdvances ;--}}
{{--            if($(this).is(':checked'))--}}
{{--            {--}}
{{--                totalNormalAdvances = sumNormalAdvances += normal_advance_amount ;--}}
{{--                normalAdvance.val(totalNormalAdvances);--}}
{{--            }--}}
{{--            else--}}
{{--            {--}}
{{--                totalNormalAdvances = sumNormalAdvances -= normal_advance_amount;--}}
{{--                normalAdvance.val(totalNormalAdvances);--}}
{{--            }--}}

{{--        });--}}
{{--    </script>--}}

    <!-- make total scheduled advance -->
    <script>
        let sum = 0;
        let scheduledAdvance = $('#scheduledAdvance');
        $(document).on('change', '.select_advance_amount', function() {
            let advance_amount = parseFloat($(this).closest('tr').find('.amount').text());
            // let advance_ids = [];
            let total ;
            console.log(advance_amount)
            if($(this).is(':checked'))
            {
                total = sum += advance_amount ;
                scheduledAdvance.val(total);
            }
            else
            {
                total = sum -= advance_amount;
                scheduledAdvance.val(total);
            }
        });
    </script>
    <!-- make total rewards -->
    <script>
        let collect = 0;
        let scheduledReward = $('#scheduledReward');
        $(document).on('change', '.select_reward_amount', function() {
            let reward_amount = parseFloat($(this).closest('tr').find('.amount').text());
            let total ;
            if($(this).is(':checked'))
            {
                total = collect += reward_amount ;
                scheduledReward.val(total);
            }
            else
            {
                total = collect -= reward_amount;
                scheduledReward.val(total);
            }

        });
    </script>

    <!-- make total discounts -->
    <script>
        let collect_2 = 0;
        let totalDiscounts = $('#totalDiscounts');
        $(document).on('change', '.select_discount_amount', function() {
            let discount_amount = parseFloat($(this).closest('tr').find('.amount').text());
            let total ;
            if($(this).is(':checked'))
            {
                total = collect_2 += discount_amount ;
                totalDiscounts.val(total);
            }
            else
            {
                total = collect_2 -= discount_amount;
                totalDiscounts.val(total);
            }

        });
    </script>

    <!-- make total vacations deducted -->
    <script>
        let collect_1 = 0;
        let vacations_deducted = $('#vacations_deducted');
        $(document).on('change', '.select_vacations_deducted_amount', function() {
            let advance_amount = parseFloat($(this).closest('tr').find('.amount').text());
            let total ;
            if($(this).is(':checked'))
            {
                total = collect_1 += advance_amount ;
                vacations_deducted.val(total);
            }
            else
            {
                total = collect_1 -= advance_amount;
                vacations_deducted.val(total);
            }
        });
    </script>

    <!-- calc final salary -->
    <script>
        calc_final_salary();
        checkFinalSalary();
        $(document).on('change', 'input[type="checkbox"]', function() {
            calc_final_salary();
            if (checkFinalSalary() < 0){
                $('input[type="submit"]').prop('disabled', true);
                $('#check_salary_value').css({'display':'block'}).text('عفوا الحد الادنى للراتب لا يسمح بخصم هذا المبلغ');
                console.log('error');
            }else
            {
                $('input[type="submit"]').prop('disabled', false);
                $('#check_salary_value').css({'display':'none'}).text('');
                console.log('ok');

            }
        });
        function calc_final_salary() {
            let total_salary = parseFloat($('#total_salary').val());
            let totalAdvances = parseFloat($('#total_advances').val());
            let scheduledAdvance = parseFloat($('#scheduledAdvance').val());

            let totalRewards = parseFloat($('#total_rewards').val());
            let scheduledReward = parseFloat($('#scheduledReward').val());

            let totalDiscounts = parseFloat($('#totalDiscounts').val());

            let vacations_deducted = parseFloat($('#vacations_deducted').val());

            // console.log(total_salary , totalAdvances , scheduledAdvance , totalRewards , scheduledReward , totalDiscounts , vacations_deducted)

            let total =  (total_salary - totalAdvances - scheduledAdvance + totalRewards + scheduledReward - totalDiscounts - vacations_deducted);
            // console.log(total_salary, totalAdvances, scheduledAdvance, totalRewards, scheduledReward, totalDiscounts, vacations_deducted)
            $('#final_salary').val(parseFloat(total).toFixed(2));

        }

        function checkFinalSalary() {
            let finalSalary = $('#final_salary').val();
            return parseInt(finalSalary);
        }
    </script>

    <script>

    </script>
@endpush
