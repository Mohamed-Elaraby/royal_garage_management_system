@extends('admin.layouts.app')

@section('title', __('trans.employee signature'))

@section('content')
{{--    @php($SaleOrderId = request('redirectToSaleOrder') ?? '')--}}

    <div class="row">
        <div class="col-xs-12">
            <div class="card">
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
                <div class="card-header" style="margin: 20px 0">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h3 style="margin: 0; padding: 0; display: inline"> <i class="fa fa-pencil-square-o"></i> {{ __('trans.employee signature') . ' ' . $employee -> name }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">



                    <div class="text-center">
                        <h2 style="margin-bottom: 5vw">[ راتب شهر {{ $previous_salary_month . '-' . $current_salary_year }} ]</h2>
                        <div id="signature"></div>
                        <button id="btn_save" onclick="showConfirmMessage($('#signature'))">حفظ التوقيع</button>
                        <button onclick="$('#signature').jSignature('clear')">اعادة التوقيع</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')
    <!-- Datatable Bootstrap Css Files -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/datatableButtonsCssFiles/buttons.dataTables.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/signature/js/jSignature.js') }}"></script>
    <!-- Custom Function -->

    <script>
        $( document ).ready(function() {
            $("#signature").jSignature(
                {
                    color:"#000",
                    lineWidth:2,
                    height:200,
                    width:400,
                    'background-color':'#FFF',
                },
            );
        });
        function importImg(sig)
        {
            console.log('start')
            let canvas_image_data = sig.jSignature('getData');
            let image_data = canvas_image_data.replace(/^data:image\/(png|jpg);base64,/, "");
            let employee_salary_log_id = '{{ $employee_salary_log_id }}';
            let url = route('employee.salaries.signature');
            let redirect_url = '{{ route('employee.salaries.salary_month_details', [$employee_id, $previous_salary_month, $current_salary_year]) }}';


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: url,
                data: {image_data:image_data, employee_salary_log_id:employee_salary_log_id},
                success: function (data) {
                    if (!data.error){
                        console.log('OK')
                    }
                }

            });
            setTimeout(function(){
                window.location.href = redirect_url;
            },500);
        }

        // Show Confirm Message For Delete Any Item
        function showConfirmMessage (signature){
            let confirmMessage = '{{ __('trans.are you sure that this is the correct signature for the employee') }}';
            let result = confirm(confirmMessage);
            if (result) importImg(signature);
        }
    </script>
@endpush

