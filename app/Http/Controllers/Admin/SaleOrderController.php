<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SaleOrderDatatable;
use App\Http\Requests\SaleOrder\AddSaleOrderRequest;
use App\Http\Requests\SaleOrder\EditOpenSaleOrder;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Check;
use App\Models\Client;
use App\Models\ClientTransaction;
use App\Models\MoneySafe;
use App\Models\OpenSaleOrder;
use App\Models\OpenSaleOrderProducts;
use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\SaleOrderProducts;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use PDF;

class SaleOrderController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware(['permission:read-saleOrders', 'checkOrdersStatus'])->only('index');
        $this->middleware('permission:create-saleOrders')->only('create');
        $this->middleware('permission:update-openSaleOrders')->only('edit');
        $this->middleware('permission:delete-openSaleOrders')->only('destroy');
    }

    public function index(SaleOrderDatatable $saleOrderDatatable)
    {
        return $saleOrderDatatable -> render('admin.saleOrders.index');
    }

    public function create()
    {
        $check_id = request('check_id');
        $check = Check::findOrFail($check_id);
        $branches = Branch::pluck('name', 'id')->toArray();
        $categories = Category::all();
        return view('admin.saleOrders.create', compact('check', 'branches', 'categories'));
    }

    public function store(AddSaleOrderRequest $request)
    {
//        dd($request->all());
        $user_id = Auth::user()->id;
        $payment_method = $request -> payment_method ?? null;
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
        $data_except_amount_paid = $request -> except(['amount_paid', 'amount_paid_bank']);
        // if amount paid request empty set amount paid equal zero
        $amount_paid = $request->amount_paid ?? 0;
        $amount_paid_bank = $request->amount_paid_bank ?? 0;
        $order_products = $request->product_data;
        $total_amounts_paid_plus_bank = $amount_paid + $amount_paid_bank ;
        $client = Client::findOrFail($request->client_id);

        if ($request -> soft_save) {
            $status = 'open';
            // create open sale order
            $invoice_number = SaleOrder::all()->last() ? SaleOrder::all()->last()->id + 1 : 1;
            $saleOrder = SaleOrder::create($data_except_amount_paid + ['user_id' => $user_id, 'invoice_number' => $invoice_number, 'amount_paid' => $amount_paid, 'amount_paid_bank' => $amount_paid_bank, 'status' => $status]);
            foreach ($order_products as $product) {
                // insert products to sale order products table with relation
                $saleOrder -> saleOrderProducts() -> create($product);

                // check product exist in product table
                $product_exist = Product::where(['branch_id' => $request -> branch_id, 'code' => $product['item_code']])->first();
//            dd($product_exist);
                if ($product_exist == true){ // if product is exist
                    // update product price and quantity in product table
                    $product_exist->quantity = $product_exist->quantity - $product['item_quantity'];
                    $product_exist->save();
                }
            }
            return redirect() -> route('admin.saleOrders.index', ['status' => 'open']) -> with('success', __('trans.open sale order added successfully'));

        }else {
            $status = 'close';
            // create sale order
            $invoice_number = SaleOrder::all()->last() ? SaleOrder::all()->last()->id + 1 : 1;
            $data_init =
                [
                    'user_id' => $user_id,
                    'invoice_number' => $invoice_number,
                    'amount_paid' => $amount_paid,
                    'amount_paid_bank' => $amount_paid_bank,
                    'status' => $status,
                    'date_of_supply' => Carbon::now()
                ];
            $saleOrder = SaleOrder::create($data_except_amount_paid + $data_init);
            $saleOrderId = $saleOrder->id;

            foreach ($order_products as $order_product) {

                // insert products to sale order products table with relation
                $saleOrder -> saleOrderProducts() -> create($order_product);

                // check product exist in product table
                $product_exist = Product::where(['branch_id' => $request -> branch_id, 'code' => $order_product['item_code']])->first();
//            dd($product_exist);
                if ($product_exist == true){ // if product is exist
                    // update product price and quantity in product table
                    $product_exist->quantity = $product_exist->quantity - $order_product['item_quantity'];
                    $product_exist->save();
                }

            }

            /* Update Supplier balance */
            $client_balance_after_add_total_amount_due = $client->balance - $request->total_amount_due ;
            $client->update(['balance' => $client_balance_after_add_total_amount_due ]);

            /* Record Transaction On Client Transaction Table */
            $this -> insertToClientTransaction(
                $saleOrder, // relatedModel
                [
                    'total_amount'                  => $request->total_amount_due,
                    'client_balance'                => $client_balance_after_add_total_amount_due,
                    'details'                       => 'فاتورة مبيعات رقم / ' . $saleOrder -> invoice_number,
                    'amount_paid'                   => $amount_paid,
                    'amount_paid_bank'              => $amount_paid_bank,
                    'amount_due'                    => $request->amount_due,
                    'transaction_date'              => $request->invoice_date,
                    'transaction_type'              => 'credit',
                    'credit'                        => $request->total_amount_due,
                    'user_id'                       => $user_id,
                    'client_id'                     => $request->client_id,
                ]
            );

            if ($total_amounts_paid_plus_bank > 0)
            {
                /* Update Supplier balance after subtract total amount paid */
                $client_balance_after_add_total_amounts_paid = $client->balance + $total_amounts_paid_plus_bank ;
                $client->update(['balance' => $client_balance_after_add_total_amounts_paid ]);

                ClientTransaction::create([
                    'total_amount'              => $total_amounts_paid_plus_bank,
                    'client_balance'            => $client_balance_after_add_total_amounts_paid,
                    'details'                   => 'سداد فاتورة مبيعات رقم / ' . $saleOrder -> invoice_number, // details
                    'amount_paid'               => $amount_paid,
                    'amount_paid_bank'          => $amount_paid_bank,
                    'amount_due'                => $request->amount_due,
                    'transaction_date'          => $request->invoice_date,
                    'transaction_type'          => 'debit',
                    'debit'                     => $total_amounts_paid_plus_bank,
                    'user_id'                   => $user_id,
                    'client_id'                 => $request->client_id,
                ]);

                /* insert into statement table */
                $statement_amount_paid = $request->amount_paid ?? null;
                $statement_amount_paid_bank = $request->amount_paid_bank ?? null;
                $total_vat = 0; // VAT removed: never trust client-supplied tax value

                $statement_amount_paid_bank_transfer = null ;

                if ($request -> payment_method_bank == 'تحويل بنكى')
                {
                    $statement_amount_paid_bank = null;
                    $statement_amount_paid_bank_transfer = $request -> amount_paid_bank;
                }
                elseif ($request -> payment_method_bank == 'شبكة')
                {
                    $statement_amount_paid_bank = $request -> amount_paid_bank;
                    $statement_amount_paid_bank_transfer = null;
                }
                elseif ($request -> payment_method_bank == 'STC-Pay')
                {
                    $statement_amount_paid_bank = $request -> amount_paid_bank;
                    $statement_amount_paid_bank_transfer = null;
                }
//                dd($statement_amount_paid, $statement_amount_paid_bank, $statement_amount_paid_bank_transfer);
                /* Record Transaction On Statement Table */
                $this -> insertToStatement(
                    $saleOrder, // relatedModel
                    [
                        'imports_cash'                  =>  $statement_amount_paid,
                        'imports_network'               =>  $statement_amount_paid_bank,
                        'imports_bank_transfer'         =>  $statement_amount_paid_bank_transfer,
                        'card_details_hand_labour'      =>  $request -> hand_labour,
                        'card_details_new_parts'        =>  $request -> new_parts,
                        'card_details_used_parts'       =>  $request -> used_parts,
                        'card_details_tax'              =>  $total_vat,
                        'notes'                         =>  'فاتورة مبيعات رقم / ' . $saleOrder -> invoice_number . ' ' . __('trans.check number') . ' ' . $saleOrder ->check -> check_number,
                        'branch_id'                     =>  $request -> branch_id,
                    ]
                );
            }

            /* Update Money Safe Amount */
            $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;
            $saleOrder->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe + ($amount_paid)),
                'user_id' => Auth::user()->id,
                'branch_id' => $request -> branch_id,
            ]);

            /* Update Bank Amount */
            $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;
            $saleOrder->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank + ($amount_paid_bank)),
                'money_process_type' => 1,
                'user_id' => Auth::user()->id,
                'branch_id' => $request -> branch_id,
            ]);


            return redirect() -> route('admin.check.clientSignature', [$saleOrder -> check -> id, $saleOrder -> check -> check_number, 'exit=true', 'redirectToSaleOrder='.$saleOrderId]) -> with('success', __('trans.sale order added successfully'));
        }
    }

    public function edit($id)
    {
        $saleOrder = SaleOrder::findOrfail($id);
        if ($saleOrder -> status == 'close')
            abort(404);
        $categories = Category::all();
        return view('admin.saleOrders.edit', compact('saleOrder', 'categories'));
    }

    public function update(EditOpenSaleOrder $request, $id)
    {
        $user_id = Auth::user()->id;
        $saleOrder = SaleOrder::findOrFail($id);
        $saleOrderId = $saleOrder -> id;
//        $saleOrderData = $request ->except(['product_data']);
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
        $data_except_amount_paid_and_payment_methods = $request -> except(['amount_paid', 'amount_paid_bank', 'invoice_number', 'payment_method', 'payment_method_bank']);
        $data_except_amount_paid = $request -> except(['amount_paid', 'amount_paid_bank', 'invoice_number']);
        // if amount paid request empty set amount paid equal zero
        $amount_paid = $request->amount_paid ?? 0;
        // if amount paid bank request empty set amount paid equal zero
        $amount_paid_bank = $request->amount_paid_bank ?? 0;
        // if amount payment method request empty set payment method null
        $payment_method = $request -> payment_method ?? null;
        // if amount payment method bank request empty set payment method bank null
        $payment_method_bank = $request -> payment_method_bank ?? null;
        $order_products = $request->product_data;
        $sold_products_list = $saleOrder ->saleOrderProducts;

        $total_amounts_paid_plus_bank = $amount_paid + $amount_paid_bank ;

        $new_products_codes_array = Arr::pluck($order_products, 'item_code');

        $client = Client::findOrFail($request->client_id);

        // ################################################## //

        // new products
        $new_quantity_with_code = [];

        foreach ($order_products as $new_product)
        {
            $code = $new_product['item_code'];
            $quantity = $new_product['item_quantity'];
            $new_quantity_with_code[$code] = isset($new_quantity_with_code[$code]) ? $new_quantity_with_code[$code] + $quantity : $quantity;

        } // end foreach

        $new_quantity_with_code = Arr::dot($new_quantity_with_code);

        // ################################################## //

        // sold products
        $sold_quantity_with_code = [];

        foreach ($sold_products_list as $sold_product)
        {
            $sold_code = $sold_product['item_code'];
            $sold_quantity = $sold_product['item_quantity'];
            $sold_quantity_with_code[$sold_code] = isset($sold_quantity_with_code[$sold_code]) ? $sold_quantity_with_code[$sold_code] + $sold_quantity : $sold_quantity;

        } // end foreach
        $sold_quantity_with_code = Arr::dot($sold_quantity_with_code);

        // ################################################## //


        foreach ($new_quantity_with_code as $new_order_product_code => $new_order_product_quantity)
        {
//            dump([$new_order_product_code => $new_order_product_quantity]);
            $product_sold = SaleOrderProducts::where(['sale_order_id' => $saleOrder -> id, 'item_code' => $new_order_product_code]) -> first();

            if ($product_sold) { // this item already sold // exist in open sale order products table
                $sold_order_product_quantity = $sold_quantity_with_code[$new_order_product_code];

                if ($sold_order_product_quantity != $new_order_product_quantity) {

                    $product_exist = Product::where(['branch_id' => $request->branch_id, 'code' => $new_order_product_code])->first();
                    // check if new product already exist in products table
                    if ($product_exist) {
                        $product_exist->update([
                            'quantity' => $product_exist->quantity + $sold_order_product_quantity - $new_order_product_quantity
                        ]);
                    }

                }
            }else { // this item already not sold // not exist in open sale order products table
                $product_exist = Product::where(['branch_id' => $request -> branch_id, 'code' => $new_order_product_code])->first();
                // check if new product already exist in products table
                if ($product_exist) {
                    $product_exist -> update([
                        'quantity' => $product_exist -> quantity - $new_order_product_quantity
                    ]);
                }
            }
        } // end foreach

        // if product exist in OpenSaleOrderProducts table and not exist in new products list remove this from OpenSaleOrderProducts table and subtract this product quantity from products table
        foreach ($sold_products_list as $product)
        {
            $product_sold_code = $product -> item_code;
            if (!in_array($product_sold_code, $new_products_codes_array)) {
                $product_sold_quantity = $product -> item_quantity;
                SaleOrderProducts::where(['sale_order_id' => $saleOrder -> id, 'item_code' => $product_sold_code]) -> delete();
                $item_exist_in_products_table = Product::where(['branch_id' => $request -> branch_id, 'code' =>
                    $product_sold_code])->first();
                if ($item_exist_in_products_table) {
                    $item_exist_in_products_table -> update([
                        'quantity' => $item_exist_in_products_table -> quantity + $product_sold_quantity
                    ]);
                }
            }
        } // end foreach

        // delete all sale order products
        $saleOrder -> saleOrderProducts () ->delete();
        // add new products to sale order products table
        foreach ($order_products as $new_product) {
        $saleOrder -> saleOrderProducts() -> create($new_product);
        }
        // update sale order data
        $init_data = [
            'amount_paid'           => $amount_paid,
            'amount_paid_bank'      => $amount_paid_bank,
            'payment_method'        => $payment_method,
            'payment_method_bank'   => $payment_method_bank
        ];
         $saleOrder -> update($data_except_amount_paid_and_payment_methods + $init_data);


        if ($request -> soft_save) {
            return redirect() -> back() -> with('success', __('trans.open sale order edit successfully'));
        }
        else {

            $saleOrder -> update(['status' => 'close', 'date_of_supply' => Carbon::now()]);
            /* Record Transaction On Client Transaction Table */
            /* Update Supplier balance */
            $client_balance_after_add_total_amount_due = $client->balance - $request->total_amount_due ;
            $client->update(['balance' => $client_balance_after_add_total_amount_due ]);

            /* Record Transaction On Client Transaction Table */
            $this -> insertToClientTransaction(
                $saleOrder, // relatedModel
                [
                    'total_amount'                  => $request->total_amount_due,
                    'client_balance'                => $client_balance_after_add_total_amount_due,
                    'details'                       => 'فاتورة مبيعات رقم / ' . $saleOrder -> invoice_number,
                    'amount_paid'                   => $amount_paid,
                    'amount_paid_bank'              => $amount_paid_bank,
                    'amount_due'                    => $request->amount_due,
                    'transaction_date'              => $request->invoice_date,
                    'transaction_type'              => 'credit',
                    'credit'                        => $request->total_amount_due,
                    'user_id'                       => $user_id,
                    'client_id'                     => $request->client_id,
                ]
            );

            if ($total_amounts_paid_plus_bank > 0)
            {
                /* Update Supplier balance after subtract total amount paid */
                $client_balance_after_add_total_amounts_paid = $client->balance + $total_amounts_paid_plus_bank ;
                $client->update(['balance' => $client_balance_after_add_total_amounts_paid ]);

                ClientTransaction::create([
                    'total_amount'              => $total_amounts_paid_plus_bank,
                    'client_balance'            => $client_balance_after_add_total_amounts_paid,
                    'details'                   => 'سداد فاتورة مبيعات رقم / ' . $saleOrder -> invoice_number, // details
                    'amount_paid'               => $amount_paid,
                    'amount_paid_bank'          => $amount_paid_bank,
                    'amount_due'                => $request->amount_due,
                    'transaction_date'          => $request->invoice_date,
                    'transaction_type'          => 'debit',
                    'debit'                     => $total_amounts_paid_plus_bank,
                    'user_id'                   => $user_id,
                    'client_id'                 => $request->client_id,
                ]);

                /* insert into statement table */
                $amount_paid = $request->amount_paid ?? null;

                $total_vat = 0; // VAT removed: never trust client-supplied tax value

                $amount_paid_bank_transfer = null ;

                if ($request -> payment_method_bank == 'تحويل بنكى')
                {
                    $amount_paid_bank = null;
                    $amount_paid_bank_transfer = $request -> amount_paid_bank;
                }
                elseif ($request -> payment_method_bank == 'شبكة')
                {
                    $amount_paid_bank = $request -> amount_paid_bank;
                    $amount_paid_bank_transfer = null;
                }
                elseif ($request -> payment_method_bank == 'STC-Pay')
                {
                    $amount_paid_bank = $request -> amount_paid_bank;
                    $amount_paid_bank_transfer = null;
                }
//dd($amount_paid, $amount_paid_bank, $amount_paid_bank_transfer);
                /* Record Transaction On Statement Table */
                $this -> insertToStatement(
                    $saleOrder, // relatedModel
                    [
                        'imports_cash'                  =>  $amount_paid,
                        'imports_network'               =>  $amount_paid_bank,
                        'imports_bank_transfer'         =>  $amount_paid_bank_transfer,
                        'card_details_hand_labour'      =>  $request -> hand_labour,
                        'card_details_new_parts'        =>  $request -> new_parts,
                        'card_details_used_parts'       =>  $request -> used_parts,
                        'card_details_tax'              =>  $total_vat,
                        'notes'                         =>  'فاتورة مبيعات رقم / ' . $saleOrder -> invoice_number . ' ' . __('trans.check number') . ' ' . $saleOrder ->check -> check_number,
                        'branch_id'                     =>  $request -> branch_id,
                    ]
                );
            }

            /* Update Money Safe Amount */
            $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;
            $saleOrder->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe + ($amount_paid)),
                'user_id' => Auth::user()->id,
                'branch_id' => $request -> branch_id,
            ]);

            /* Update Bank Amount */
            $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;
            $saleOrder->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank + ($amount_paid_bank)),
                'money_process_type' => 1,
                'user_id' => Auth::user()->id,
                'branch_id' => $request -> branch_id,
            ]);
            return redirect() -> route('admin.check.clientSignature', [$saleOrder -> check -> id, $saleOrder -> check -> check_number, 'exit=true', 'redirectToSaleOrder='.$saleOrderId]) -> with('success', __('trans.sale order added successfully'));

//            return redirect() -> route('admin.saleOrders.show', $saleOrderId) -> with('success', __('trans.sale order added successfully'));

        }
    }

    public function show($id)
    {
        $sale_order = SaleOrder::findOrFail($id);
        if ($sale_order -> status == 'open')
            abort(404);
//            return redirect() -> route('admin.purchaseOrders.index', ['status' => 'open']);
        return view('admin.saleOrders.show_invoice', compact('sale_order'));
    }

    public function destroy($id)
    {
        $saleOrder = SaleOrder::findOrFail($id);

        $saleOrderProducts = $saleOrder ->saleOrderProducts;
        foreach ($saleOrderProducts as $product) {
            $item_in_products_table = Product::where(['branch_id' => $saleOrder -> branch_id,'code' => $product -> item_code])->first();
            if ($item_in_products_table) {
                $item_in_products_table -> update([
                    'quantity' => $item_in_products_table -> quantity + $product -> item_quantity
                ]);
            }
        }

        $saleOrder ->delete();
        return redirect() -> back() ->with('error', __('trans.open sale order delete successfully'));
    }

    public function get_all_products_in_branch(Request $request)
    {
        if ($request -> ajax())
        {

            $branch_id = $request -> branch_id;
            $category_id = $request -> category;
            $keyword = $request -> keyword;

            if ($branch_id)
            {

                $result = Product::query()
                    ->where('branch_id' , $branch_id)
                    ->where(function ($query)use ($keyword){
                        if ($keyword)
                        {
                            $query
                                -> where('code' , 'like', '%'.$keyword.'%')
                                -> orwhere('name' , 'like', '%'.$keyword.'%');
                        }
                    })
                    ->where(function ($query)use ($category_id){
                        if ($category_id)
                        {
                            $query -> where('sub_category_id' , $category_id);
                        }
                    })
                    -> orderByDesc('quantity')
                    -> with('subCategory')
                    -> get();

                return response()->json(['result' => $result], 200);
            } // end if branch selected return value
        } // end if ajax
    }

    public function get_all_products_in_branch_edit(Request $request)
    {
        if ($request -> ajax())
        {

            $branch_id = $request -> branch_id;
            $category_id = $request -> category;
            $keyword = $request -> keyword;
            $sale_order_id = $request -> sale_order_id;
            $codes = [];
//            $products = SaleOrderProducts::where('sale_order_id', $sale_order_id) -> get();
            $sold_products_list = SaleOrderProducts::where('sale_order_id', $sale_order_id) -> get();
//            foreach ($sold_products_list as $product) {
//                $codes ['code'] = $product -> item_code;
//                $codes ['quantity'] = $product -> item_quantity;
//            dump($codes);
//            }

            if ($branch_id)
            {

                $result = Product::query()
                    ->where('branch_id' , $branch_id)
                    ->where(function ($query)use ($keyword){
                        if ($keyword)
                        {
                            $query
                                -> where('code' , 'like', '%'.$keyword.'%')
                                -> orwhere('name' , 'like', '%'.$keyword.'%');
                        }
                    })
                    ->where(function ($query)use ($category_id){
                        if ($category_id)
                        {
                            $query -> where('sub_category_id' , $category_id);
                        }
                    })
                    -> orderByDesc('quantity')
                    -> with('subCategory')
                    -> get();

//                $result -> map(function ($coll, $index)use($codes){
//
//                    if (in_array($coll ['code'], $codes)) {
//                        $coll -> quantity = $coll->quantity + $codes['quantity'];
//                    }
//
//
//                });
//                    dd($result);

                // sold products
                $sold_quantity_with_code = [];

                foreach ($sold_products_list as $sold_product)
                {
                    $sold_code = $sold_product['item_code'];
                    $sold_quantity = $sold_product['item_quantity'];
                    $sold_quantity_with_code[$sold_code] = isset($sold_quantity_with_code[$sold_code]) ? $sold_quantity_with_code[$sold_code] + $sold_quantity : $sold_quantity;

                } // end foreach
//                $sold_quantity_with_code = Arr::dot($sold_quantity_with_code);

                foreach ($sold_quantity_with_code as $code => $quantity) {
                    $collection = $result -> map(function ($value)use($code, $quantity){
                        if ($value -> code == $code) {
                            return $value -> quantity = $value -> quantity + $quantity;
                        }
                    });
                }
//                dump($result);

//                dump(collect($new_result)->toJson());
                return response()->json(['result' => $result], 200);
            } // end if branch selected return value
        } // end if ajax
    }

    public function saleOrderProducts($sale_order_id)
    {
        // show sale orders products Page
        $saleOrder = SaleOrder::findOrFail($sale_order_id); // show products for sale order
        return view('admin.saleOrders.orderProducts', compact('saleOrder'));
    }

    public function getClientBalance(Request $request)
    {
        if ($request -> ajax())
        {
            $client_id = $request -> client_id;
            if ($client_id)
            {
                $client_balance = Client::find($client_id)->balance;
                return response()->json($client_balance, 200);
            }
        }
    }

    public function tax_invoice(Request $request)
    {
        $saleOrderId = $request -> sale_order_id;
//        $data = [];
        $data['sale_order'] = SaleOrder::findOrFail($saleOrderId);
        $mpdf = PDF::loadView('admin.saleOrders.tax_invoice', $data, [], [
            'margin_top' => 140,
            'margin_header' => 10,
            'margin_footer' => 20,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;
//         $mpdf->download($data['sale_order']->invoice_number.'.pdf');
        if ($request->download)
        {
            return $mpdf->download($data['sale_order']->invoice_number.'.pdf');

        }
        return $mpdf->stream($data['sale_order']->invoice_number.'.pdf');
    }

    public function simplified_tax_invoice(Request $request)
    {
        $saleOrderId = $request -> sale_order_id;
//        $data = [];
        $data['sale_order'] = SaleOrder::findOrFail($saleOrderId);
        $mpdf = PDF::loadView('admin.saleOrders.simplified_tax_invoice', $data, [], [
            'margin_top' => 140,
            'margin_header' => 10,
            'margin_footer' => 20,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;
//         $mpdf->download($data['sale_order']->invoice_number.'.pdf');
        if ($request->download)
        {
            return $mpdf->download($data['sale_order']->invoice_number.'.pdf');

        }
        return $mpdf->stream($data['sale_order']->invoice_number.'.pdf');
    }

}
