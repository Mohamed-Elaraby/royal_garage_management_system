@extends('admin.layouts.app')

@section('title', __('trans.all car size'))

@section('content')
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
                        <div class="col-xs-12">
                            <h3 style="margin: 0; padding: 0; display: inline"> <i class="fa fa-car"></i> {{ request('car_type_id')?__('trans.all car size').'['.$carTypeName.']': __('trans.all car size')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped']) !!}
                    @include('admin.includes.bootstrap_model.carModel')
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
    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <style>
            .date_dir_setting
            {
                direction:ltr;
                text-align: right;
            }
        </style>
    @endif
@endpush

@push('scripts')
    <!-- Datatable Bootstrap JavaScript Files -->
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
    {!! $dataTable->scripts() !!}
    <!-- Custom Function -->

    <script>
        $(document).on('change', '.selectAction', function () {
            let url = $(this).children('option:selected').attr('value');
            location.href = url;
        }) // end on change
    </script>

    <script>
        // Show Confirm Message For Delete Any Item
        function showDeleteMessage (){
            let deleteMessage = '{{ __('trans.are you sure delete this') }}';
            return confirm(deleteMessage);
        }

        //
        $(document).on('change', '#carSizeRelated', function (e) {
            window.location.href = $(this).find(':selected').data('url');
        })
    </script>

{{--    <script>--}}
{{--        $( document ).ready( function () {--}}

{{--            $( document ).on('click', '#carId', function () {--}}
{{--                let carSizeId = $( this ).data('id');--}}
{{--                let carModel = {!! $carModel !!};--}}
{{--                $('#save_data').attr('value', carSizeId);--}}
{{--                $.ajax({--}}
{{--                    headers: {--}}
{{--                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                    },--}}
{{--                    url: '{{ route('admin.car.getCarModelByAjax') }}',--}}
{{--                    method: 'POST',--}}
{{--                    data: {carSizeId},--}}
{{--                    success: function (data) {--}}
{{--                        $('#show').empty();--}}
{{--                        $('#messagesSection').empty();--}}
{{--                        $(carModel).each(function (carModel_index, carModel_element)--}}
{{--                        {--}}

{{--                            $('#show').append('<input class="myCheck" type="checkbox" name="name[]" id="name" value="'+carModel_element.id+'">'+carModel_element.name);--}}

{{--                            $(data).each(function (index, element){--}}

{{--                                $(':checkbox[value='+element+']').prop('checked', 'enabled');--}}

{{--                            }) // end data each--}}

{{--                        }) // end carModel each--}}
{{--                    }--}}
{{--                });--}}
{{--            })--}}
{{--        })--}}
{{--    </script>--}}

{{--    <script>--}}
{{--        $( document ).ready( function () {--}}
{{--            $( document ).on('click', '#save_data', function () {--}}
{{--                let carSizeValue = $(this).val();--}}
{{--                let carModelIds = [];--}}
{{--                $("input[type=checkbox]:checked").each(function () {--}}
{{--                    carModelIds.push($(this).val());--}}
{{--                })--}}
{{--                if (carModelIds.length < 1)--}}
{{--                {--}}
{{--                    $('#messagesSection').html("<p class='alert alert-danger'>مطلوب تحديد موديل واحد على الاقل.</p>")--}}
{{--                }else {--}}
{{--                    $.ajax({--}}
{{--                        headers: {--}}
{{--                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                        },--}}
{{--                        url: '{{ route('admin.saveCarModelByAjax') }}',--}}
{{--                        method: 'POST',--}}
{{--                        data: {carSizeValue, carModelIds},--}}
{{--                        success: function () {--}}
{{--                            if (!$('#messagesSection').is(':empty') && $('#messagesSection').text() != 'مطلوب تحديد موديل واحد على الاقل.'){--}}
{{--                                $('#messagesSection').fadeOut( 400 ).delay( 200 ).fadeIn( 400 );--}}
{{--                            }--}}
{{--                            $('#messagesSection').html("<p class='text-center alert alert-success'>تم الحفظ بنجاح.</p>");--}}
{{--                        }--}}
{{--                    })--}}
{{--                }--}}

{{--            });--}}
{{--        });--}}


{{--    </script>--}}
@endpush
