@php
    $pageType = __('trans.log');
    $pageItem = __('trans.client transactions')
@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @if($client)

                <div class="col-xs-12">
                    <div class="card-header">
                        <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem . ' [ '. $client -> name.' ] '}}</h3>
                        <h4 class="text-center">
                            <i class="fa fa-money"></i> {{ __('trans.balance') .' [ '.$client -> balance.' ] '}}

                            @if (Auth::user()->hasPermission('create-clientPayments'))
                                <a href="{{ route('admin.clientPayments.create', ['client_id' => $client->id]) }}">{{ __('trans.pay')}}</a>
                            @endif
                            |
                            @if (Auth::user()->hasPermission('create-clientCollecting'))
                                <a href="{{ route('admin.clientCollecting.create', ['client_id' => $client->id]) }}">{{ __('trans.collect')}}</a>
                            @endif

                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table_container_only_for_responsive">
                            <table class="table table-responsive table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('trans.total') }}</th>
                                    <th>{{ __('trans.amount paid in money safe') }}</th>
                                    <th>{{ __('trans.amount paid in bank') }}</th>
                                    <th>{{ __('trans.amount due') }}</th>
                                    <th>{{ __('trans.source number') }}</th>
                                    <th>{{ __('trans.transaction date') }}</th>
                                    <th>{{ __('trans.editor') }}</th>
                                    <th>{{ __('trans.source') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($transactions  as $key => $transaction)
                                    <tr>
                                        <td>{{ $key+1  }}</td>
                                        <td>{{ $transaction -> total_amount }}</td>
                                        <td>{{ $transaction -> amount_paid?? '-' }}</td>
                                        <td>{{ $transaction -> amount_paid_bank?? '-' }}</td>
                                        <td>{{ $transaction -> amount_due?? '-' }}</td>
                                        <td>
                                            @if ($transaction->saleOrder)
                                                {{ $transaction -> saleOrder -> invoice_number }}
                                            @elseif($transaction->clientPayment)
                                                {{ $transaction -> clientPayment -> receipt_number }}
                                            @elseif($transaction->clientCollecting)
                                                {{ $transaction -> clientCollecting -> receipt_number }}
                                            @elseif($transaction->saleOrderReturn)
                                                {{ $transaction -> saleOrderReturn -> invoice_number }}
                                            @endif
                                        </td>
                                        <td>{{ $transaction -> transaction_date }}</td>
                                        <td>{{ $transaction -> user -> name }}</td>
                                        <td>
                                            @if ($transaction->saleOrder)
                                                <a class="btn btn-warning"
                                                   href="{{ route('admin.saleOrders.show', $transaction -> saleOrder -> id) }}">{{ __('trans.sale order') }}</a>
                                            @elseif($transaction->clientPayment)
                                                <a class="btn btn-danger"
                                                   href="{{ route('admin.clientPayments.show', $transaction -> clientPayment -> id) }}">{{ __('trans.client payment') }}</a>
                                            @elseif($transaction->clientCollecting)
                                                <a class="btn btn-success"
                                                   href="{{ route('admin.clientCollecting.show', $transaction -> clientCollecting -> id) }}">{{ __('trans.supplier collecting') }}</a>
                                            @elseif($transaction->saleOrderReturn)
                                                <a class="btn btn-primary"
                                                   href="{{ route('admin.saleOrderReturns.show', $transaction -> saleOrderReturn -> id) }}">{{ __('trans.sale order return') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $transactions -> links() }}
                    </div>
                </div>

            @endif
        </div>
    </div>
@endsection
