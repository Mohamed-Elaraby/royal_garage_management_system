@extends('admin.layouts.app')

@section('title', __('trans.bank'))

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
                    <h3 class="text-center"><i class="fa fa-money"></i>{{ __('trans.bank') .' [ '. $branch -> display_name .' ]' }}</h3>
                </div>
                <div class="card-body">
                    <div class="text-center" style="width: 100%;">
                        <div style="
                                width: 50%;
                                margin: auto;
                                border: #000000 solid 1px;
                                background-color: #3c8dbc;
                                color: #FFFFFF;
                                padding: 40px;
                                border-radius: 5px;
                                font-size: 3vw;
                                box-shadow: #0a0a0a 5px 5px;
                            ">
                            {{ number_format($bank ? $bank : 0 , 2) . ' '}}دولار
                        </div>
                        <br><br><br>
                        @if (Auth::user()->hasPermission(['create-bank']))
                            <div>
                                <a class="btn btn-success" href="{{ route('admin.bank.operations', $branch -> id) }}">سحب / ايداع</a>
                                <a class="btn btn-danger" href="{{ route('admin.bank_log', ['branch_id' => $branch -> id]) }}">سجل عمليات البنك</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')

@endpush

@push('scripts')

@endpush

