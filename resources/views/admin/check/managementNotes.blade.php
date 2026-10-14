@php
if ($check -> management_notes) {
    $pageType = __('trans.edit');
}else {
    $pageType = __('trans.create');
}
    $pageItem = __('trans.management notes')
@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-6 center-block" style="float: none">
            <div class="card card-primary mt-5">
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
                    <h3 class="text-center"><i class="fa fa-user"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.check.managementNotesUpdate', $check -> id], 'method' => 'post']) !!}
                    @if (Auth::user()->hasPermission('create-managementNotes') || Auth::user()->hasPermission('update-managementNotes'))
                        <div class="form-group">
                            {!! Form::label('management_notes', __('trans.management notes'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('management_notes', $check -> management_notes, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit($pageType, ['class' => 'form-control btn btn-primary']) !!}
                        </div>
                    @else
                        <textarea class="form-control" rows="10" disabled readonly>{{ $check -> management_notes }}</textarea>
                    @endif
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('scripts')
<script>
    $(document).ready(function () {
        $('select[name=car_type_id]').on('change', function () {
            let car_type_id = $(this).children(':selected').val();
            let car_size_element = $('#car_sizes_id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.car.getCarSizesByAjax') }}',
                method: 'POST',
                data: {car_type_id:car_type_id},
                success: function (data) {
                    if (!data.error)
                    {
                        car_size_element.empty();
                        $.each(data, function (index, element){
                            car_size_element.append('<option value="'+element.id+'">'+element.name+'</option>');
                        })
                    }
                }
            })
        })
    })
</script>
@endpush
