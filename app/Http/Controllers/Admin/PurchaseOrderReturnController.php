<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PurchaseOrderReturnDatatable;
use App\Models\Bank;
use App\Models\MoneySafe;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProducts;
use App\Models\PurchaseOrderReturn;
use App\Models\PurchaseOrderReturnProducts;
use App\Models\Statement;
use App\Models\Supplier;
use App\Models\SupplierTransaction;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderReturnController extends Controller
{
    use HelperTrait;
    public function index(PurchaseOrderReturnDatatable $orderReturnDatatable)
    {
        return $orderReturnDatatable -> render('admin.purchaseOrderReturns.index');
    }

    public function create()
    {
        $purchase_order_id = request('purchase_order_id');
        $purchaseOrder = PurchaseOrder::findOrFail($purchase_order_id);
        $purchaseOrderProducts = PurchaseOrderProducts::where('purchase_order_id', $purchase_order_id)->get();
        $orderReturn_ids = collect($purchaseOrder->purchaseOrderReturns) -> pluck('id')-> toArray();
        $purchaseOrderReturnProducts = PurchaseOrderReturnProducts::whereIn('purchase_order_return_id', $orderReturn_ids)->get();

        // new products
        $new_quantity_with_code = [];

        foreach ($purchaseOrderReturnProducts as $new_product)
        {
            $code = $new_product['item_code'];
            $quantity = $new_product['item_quantity'];
            $new_quantity_with_code[$code] = isset($new_quantity_with_code[$code]) ? $new_quantity_with_code[$code] + $quantity : $quantity;

        } // end foreach

        $new_quantity_with_code = Arr::dot($new_quantity_with_code);

        foreach ($new_quantity_with_code as $code => $quantity) {
            $collection = $purchaseOrderProducts -> map(function ($value)use($code, $quantity){
                if ($value -> item_code == $code) {
                    return $value -> item_quantity = $value -> item_quantity - $quantity;
                }
            });
        }

        return view('admin.purchaseOrderReturns.create', compact('purchaseOrder', 'purchaseOrderProducts', 'purchaseOrderReturnProducts'));
    }

    public function store(Request $request)
    {
        $amount_paid = [];
        $amount_paid_bank = [];
        $amount_post = [];
        $supplier_id = PurchaseOrder::findOrFail($request -> purchase_order_id) -> supplier_id;
        $supplier = Supplier::findOrFail($supplier_id);
        $purchase_order_id = $request -> purchase_order_id;
        $branch_id = $request -> branch_id;
        $user_id = Auth::user()->id;
        $invoice_number = PurchaseOrderReturn::all()->last() ? PurchaseOrderReturn::all()->last()->id + 1 : 1;
        $invoice_date = Carbon::now()->toDateString();
        // NaN guard: never save NaN/Infinity money values sent from the browser
        $nan_values = $request->only(['total', 'discount', 'taxable_amount', 'total_amount_due', 'total_return_items']);
        foreach ((array) $request->product_data as $nan_row) {
            foreach (['item_price', 'item_quantity', 'item_amount_taxable', 'item_sub_total'] as $nan_key) {
                if (is_array($nan_row) && isset($nan_row[$nan_key])) { $nan_values[] = $nan_row[$nan_key]; }
            }
        }
        foreach ($nan_values as $nan_value) {
            if (is_string($nan_value) && preg_match('/^\s*-?(nan|infinity)\s*$/i', $nan_value)) {
                return redirect()->back()->withInput()->withErrors(['nan' => 'توجد قيمة غير رقمية (NaN) في الفاتورة، راجع الأسعار والكميات.']);
            }
        }
        // VAT removed: force tax fields to zero regardless of client input
        $request->merge(['total_vat' => 0, 'card_details_tax' => 0]);
        if (is_array($request->product_data)) {
            $request->merge(['product_data' => array_map(function ($vat_free_row) {
                if (is_array($vat_free_row)) { $vat_free_row['item_tax_amount'] = 0; }
                return $vat_free_row;
            }, $request->product_data)]);
        }
        $purchase_order_return_data = $request->except('product_data');
        $purchase_order_return_products = $request -> product_data;
        $data_init =
            [
                'user_id' => $user_id,
                'invoice_number' => $invoice_number,
                'invoice_date' => $invoice_date,
            ];
//        dd($purchase_order_return_data);
        $purchase_order_return = PurchaseOrderReturn::create($purchase_order_return_data + $data_init);
        $purchase_order_return_id = $purchase_order_return -> id;

        foreach ($purchase_order_return_products as $product) {
            if (isset($product ['checked'])) {

                $purchase_order_return -> purchaseOrderReturnProducts() -> create($product);

                $item_code = $product ['item_code'];
                $item_quantity = $product ['item_quantity'];
                $item_sub_total = $product ['item_sub_total'];
                $return_amount_in = $product ['return_amount_in'];

                // return quantity in products table
                $item_exist_in_products_table = Product::where(['branch_id' => $branch_id, 'code' => $item_code])->first();
                if ($item_exist_in_products_table) {
                    $item_exist_in_products_table -> update([
                        'quantity' => $item_exist_in_products_table -> quantity - $item_quantity
                    ]);
                }

                if ($return_amount_in == 'money_safe') {
                    /* Update Money Safe Amount */
                    $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
                    $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;
                    $purchase_order_return->moneySafes()->create([
                        'amount_paid' => $item_sub_total,
                        'final_amount' => ($final_amount_money_safe + ($item_sub_total)),
                        'user_id' => Auth::user()->id,
                        'branch_id' => $request -> branch_id,
                    ]);
                    array_push($amount_paid, $item_sub_total);


                }elseif ($return_amount_in == 'bank') {
                    /* Update Bank Amount */
                    $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
                    $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;
                    $purchase_order_return->banks()->create([
                        'amount_paid' => $item_sub_total,
                        'final_amount' => ($final_amount_bank + ($item_sub_total)),
                        'money_process_type' => 1,
                        'user_id' => Auth::user()->id,
                        'branch_id' => $request -> branch_id,
                    ]);
                    array_push($amount_paid_bank, $item_sub_total);

//                    /* Record Transaction On Statement Table */
//                    $this ->insertToStatement(
//                        $purchase_order_return, // relatedModel
//                        [
//                            'expenses_network'              =>  $product ['item_sub_total'],
//                            'notes'                         =>  'فاتورة مردودات مشتريات رقم / ' . $purchase_order_return -> invoice_number,
//                            'branch_id'                     =>  $request -> branch_id,
//                        ]
//                    );
                }elseif ($return_amount_in == 'supplier_balance') {
//                    /* Update supplier balance */
//                    $supplier_target = Supplier::whereHas('purchaseOrders', function ($query)use ($purchase_order_id){
//                        $query -> where('id', $purchase_order_id);
//                    })->first();
//                    if ($supplier_target)
//                    {
//                        $supplier_target -> update(
//                            [
//                                'balance' => $supplier_target->balance - $item_sub_total
//                                                            // 200      - 13627.5 = -13427.5
//
//
//                            ]
//                        );
//                    }
                    array_push($amount_post, $item_sub_total);
                }
            }
        }

        /* Record Transaction On Client Transaction Table */
        $amount_paid = array_sum($amount_paid)?? 0;
        $amount_paid_bank = array_sum($amount_paid_bank) ?? 0;
        $amount_post = array_sum($amount_post);
        $total_amounts = $amount_paid + $amount_paid_bank + $amount_post;
        $total_amount_paid_plus_bank = $amount_paid + $amount_paid_bank;
//        dd($amount_post);

        /* Update Supplier balance after subtract total amounts */
        $supplier_balance_after_subtract_total_amounts = $supplier->balance - $total_amounts ;
        $supplier->update(['balance' => $supplier_balance_after_subtract_total_amounts ]);
        $this -> insertTosupplierTransaction($purchase_order_return,
            [
                'total_amount'                                  => $total_amounts,
                'supplier_balance'                              => $supplier_balance_after_subtract_total_amounts,
                'details'                                       => 'فاتورة مردودات مشتريات رقم / ' . $purchase_order_return -> invoice_number,
                'amount_paid'                                   => $amount_paid,
                'amount_paid_bank'                              => $amount_paid_bank,
                'amount_paid_subtract_from_supplier_balance'    => $amount_post,
                'transaction_date'                              => $invoice_date,
                'transaction_type'                              => 'credit',
                'credit'                                        => $total_amounts,
                'user_id'                                       => $user_id,
                'supplier_id'                                   => $supplier_id
            ]
        );

        if ($total_amount_paid_plus_bank > 0 )
        {
            /* Update Supplier balance after add total amounts paid plus bank*/
            $supplier_balance_after_add_total_amount_paid_plus_bank = $supplier->balance + $total_amount_paid_plus_bank ;
            $supplier->update(['balance' => $supplier_balance_after_add_total_amount_paid_plus_bank ]);

            SupplierTransaction::create([
                'total_amount'                          => $total_amount_paid_plus_bank,
                'supplier_balance'                      => $supplier_balance_after_add_total_amount_paid_plus_bank,
                'details'                               => 'تحصيل نقدى من قيمة فاتورة مردودات مشتريات رقم / ' . $purchase_order_return -> invoice_number,
                'amount_paid'                           => $amount_paid,
                'amount_paid_bank'                      => $amount_paid_bank,
                'transaction_date'                      => $invoice_date,
                'transaction_type'                      => 'debit',
                'debit'                                 => $total_amount_paid_plus_bank,
                'user_id'                               => $user_id,
                'supplier_id'                           => $supplier_id,
            ]);
        }

        if ($amount_paid_bank > 0)
        {
            /* insert record under field custody administration network */
            Statement::create([
                'custody_administration_network'    => '-'.$amount_paid_bank,
                'notes'                             => 'عهدة من الادارة',
                'branch_id'                         =>  $request -> branch_id,
            ]);
        }

        /* Record Transaction On Statement Table */
        $this ->insertToStatement(
            $purchase_order_return, // relatedModel
            [
                'expenses_cash'                     =>  $amount_paid ? '-'.$amount_paid : null,
                'expenses_network'                  =>  $amount_paid_bank ? '-'.$amount_paid_bank : null,
                'notes'                             =>  'فاتورة مردودات مشتريات رقم / ' . $purchase_order_return -> invoice_number,
                'branch_id'                         =>  $request -> branch_id,
            ]
        );

        return redirect() -> route('admin.purchaseOrderReturns.show', $purchase_order_return_id);
    }

    public function show($id)
    {
        $purchase_order_returns = PurchaseOrderReturn::findOrFail($id);
        return view('admin.purchaseOrderReturns.show_invoice', compact('purchase_order_returns'));
    }

    public function purchaseOrderReturnProducts($purchase_order_return_id)
    {
        // show purchase orders products Page
        $purchaseOrderReturns = PurchaseOrderReturn::findOrFail($purchase_order_return_id); // show products for purchase order
        return view('admin.purchaseOrderReturns.orderProducts', compact('purchaseOrderReturns'));
    }
}
