@php
$pageType = __('trans.create');
$pageItem = __('trans.product code')

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
                    <h3 class="text-center"><i class="fa fa-briefcase"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    <div style="margin: auto; width: 60%">
                        <div class="btn btn-primary form-control" id="addNewRow">اضافة صف جديد</div>
                    </div>
                    {!! Form::open(['route' => 'admin.productCodes.store', 'method' => 'post']) !!}
                        <table class="table table-bordered">
                            <thead>
                                <th></th>
                                <th></th>
                                <th>{{ __('trans.product code') }}</th>
                                <th>{{ __('trans.product name') }}</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="form-group">
                            <input type="submit" class="form-control btn btn-success" value="{{ $pageType }}">
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            let tableContent,
                selectedRow,
                count = 1;
            $('#addNewRow').on('click', function () {

                tableContent =
                    '<tr>\n' +
                    '    <td>\n' +
                    '       <div class="btn btn-danger remove_row"><i class="fa fa-remove"></i></div> \n' +
                    '    </td>\n' +
                    '    <td>\n' +
                    '       <div class="btn btn-default">'+ count +'</div> \n' +
                    '    </td>\n' +
                    '    <td>\n' +
                    '        <input class="code form-control" type="text" name="data['+ count +'][code]" id="code_'+ count +'">\n' +
                    '    </td>\n' +
                    '    <td>\n' +
                    '        <input class="name form-control" type="text" name="data['+ count +'][name]" id="name_'+ count +'">\n' +
                    '    </td>\n' +
                    '</tr>';
                $('table tbody').prepend(tableContent);
                count ++
            })

            $(document).on('click', '.remove_row', function () {
                selectedRow = $(this).closest('tr');
                selectedRow.remove();
            })

        })
    </script>
@endpush
