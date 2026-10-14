@php
    $pageType = __('trans.show');
    $pageItem = __('trans.related products')
@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-header">
                <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem . ' [ '. $purchaseOrderReturns -> invoice_number .' ] '}}</h3>
            </div>
            <div class="card-body">
                <table class="table table-responsive table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('trans.product code') }}</th>
                        <th>{{ __('trans.product name') }}</th>
                        <th>{{ __('trans.quantity') }}</th>
                        <th>{{ __('trans.purchasing price') }}</th>
                        <th>{{ __('trans.amount taxable') }}</th>
                        <th>{{ __('trans.discount') }}</th>
                        <th>{{ __('trans.discount amount') }}</th>
                        <th>{{ __('trans.total after discount') }}</th>
                        <th>{{ __('trans.tax amount') }}</th>
                        <th>{{ __('trans.item sub total including vat') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($purchaseOrderReturns -> purchaseOrderReturnProducts as $key => $product)
                        <tr>
                            <td>{{ $key+1  }}</td>
                            <td>{{ $product -> item_code }}</td>
                            <td>{{ $product -> item_name }}</td>
                            <td>{{ $product -> item_quantity }}</td>
                            <td>{{ $product -> item_price }}</td>
                            <td>{{ $product -> item_amount_taxable }}</td>
                            <td>{{ $product -> item_discount }}{{ $product -> item_discount ? $product -> item_discount_type == 0 ? ' دولار ' : ' % ' : ''}}</td>
                            <td>{{ $product -> item_discount_amount }}</td>
                            <td>{{ $product -> item_sub_total_after_discount }}</td>
                            <td>{{ $product -> item_tax_amount }}</td>
                            <td>{{ $product -> item_sub_total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
