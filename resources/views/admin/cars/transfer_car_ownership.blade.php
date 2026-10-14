@extends('admin.layouts.app')

@section('title', __('trans.transfer car ownership'))

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
                            <h4 style="text-align: center">{{ __('trans.transfer car ownership') . ' رقم ['. $car -> plate_number .' ] الخاصة بالعميل [ ' . $car -> client -> name .' ] '}}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="main-sec" style="width: 50%; margin: auto">
                        {!! Form::open(['route' => ['admin.car.transfer_car_ownership', $car -> id], 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('client_id', ' الى '. __('trans.client'), ['class' => 'control-label']) !!}
{{--                            {!! Form::select('client_id', null, null, ['class' => 'form-control client_id']) !!}--}}
                            <select class="client_id form-control" name="client_id"></select>
                        </div>
                        <div class="form-group">
                            {!! Form::submit(__('trans.transport'), ['class' => 'form-control btn btn-success']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Select2 -->
{{--    <link rel="stylesheet" href="{{ asset('assets/bower_components/select2/dist/css/select2.min.css') }}">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('scripts')
    <!-- Select2 -->
{{--    <script src="{{ asset('assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            let the_car_owner_id = '{{ $car -> client -> id }}';
            // $('#client_id').select2();
            $('.client_id').select2({
                placeholder: 'اختر العميل المراد نقل السيارة اليه',
                ajax: {
                    url: '{{ route('admin.car.dataAjax') }}'+'?the_car_owner_id='+the_car_owner_id,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        })
    </script>
@endpush

