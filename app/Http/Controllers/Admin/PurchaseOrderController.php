<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PurchaseOrderDatatable;
use App\Http\Requests\PurchaseOrder\AddPurchaseOrder;
use App\Http\Requests\PurchaseOrder\EditOpenPurchaseOrder;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Category;
use App\Models\MoneySafe;
use App\Models\OpenPurchaseOrder;
use App\Models\Product;
use App\Models\ProductCode;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProducts;
use App\Models\Statement;
use App\Models\Supplier;
use App\Models\SupplierTransaction;
use App\Models\User;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use PDF;

class PurchaseOrderController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware(['permission:read-purchaseOrders', 'checkOrdersStatus'])->only('index');
        $this->middleware('permission:create-purchaseOrders')->only('create');
        $this->middleware('permission:update-openPurchaseOrders')->only('edit');
        $this->middleware('permission:delete-openPurchaseOrders')->only('destroy');
    }

    public function index(PurchaseOrderDatatable $purchaseDatatable, Request $request)
    {
        return $purchaseDatatable -> render('admin.purchaseOrders.index');
    }

    public function create()
    {
        if (!request('supplier_id'))
            abort('404');
        $suppliers_id = request('supplier_id') ;
        $target_supplier = Supplier::findOrFail($suppliers_id);
        $suppliers = Supplier::pluck('name', 'id')->toArray();
        $branches = Branch::pluck('name', 'id')->toArray();
        $categories = Category::select('id', 'name')->with('subCategories')->get() -> toJson();
//        dd($categories);
//        $productCodes = ProductCode::all();
        return view('admin.purchaseOrders.create', compact('suppliers', 'branches', 'categories', 'target_supplier'));
    }

//    public function store(AddPurchaseOrder $request)
    public function store(Request $request)
    {
//        dd($request->all());
        $user_id = Auth::user()->id;
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
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier_discount_amount = '';

        if ($request -> soft_save) {
            $status = 'open';
            // create open purchase order
            $openPurchaseOrder = PurchaseOrder::create($data_except_amount_paid + ['user_id' => $user_id, 'amount_paid' => $amount_paid, 'amount_paid_bank' => $amount_paid_bank, 'status' => $status]);
            foreach ($order_products as $order_product) {
                // insert products to purchase order products table with relation
                $openPurchaseOrder -> purchaseOrderProducts() -> create($order_product);
            }

            return redirect() -> route('admin.purchaseOrders.index', ['status' => $status]) -> with('success', __('trans.open purchase order added successfully'));

        }else {
            $status = 'close';
            /* Check if purchase order amount less than safe money amount redirect to link */
            $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;

            if ($final_amount_money_safe < $amount_paid){ // on mount in the safe is not enough redirect

                return redirect()->route('admin.purchaseOrders.index', ['status' => $status])->with('delete', __('trans.the amount in the safe is not enough'));

            }

            /* Check if purchase order amount less than safe money amount redirect to link */
            $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;

            if ($final_amount_bank < $amount_paid_bank){ // on mount in the bank is not enough redirect

                return redirect()->route('admin.purchaseOrders.index', ['status' => $status])->with('delete', __('trans.the amount in the bank is not enough'));

            }


            // create purchase order
            $purchaseOrder = PurchaseOrder::create($data_except_amount_paid + ['user_id' => $user_id, 'amount_paid' => $amount_paid, 'amount_paid_bank' => $amount_paid_bank, 'status' => $status]);
            $purchaseOrderId = $purchaseOrder->id;


            foreach ($order_products as $order_product) {
//                dd($order_product);
                // insert products to purchase order products table with relation
                $purchaseOrder -> purchaseOrderProducts() -> create($order_product);

                // check product exist in product table
                $product_exist = Product::where(['branch_id' => $request -> branch_id, 'code' => $order_product['item_code']])->first();
                $discount_amount_or_percentage = $order_product['item_discount_type'] == 1 ? ( $order_product['item_price'] * $order_product['item_discount'] / 100): $order_product['item_discount'];
                $item_price_after_discount_init = $order_product['item_price'] - $discount_amount_or_percentage;

                $discount_type_init = empty($order_product['item_discount']) ? null : $order_product['item_discount_type'] ;
                $discount_amount_init = empty($order_product['item_discount']) ? null : $order_product['item_discount_amount'] ;
//                dd($item_price_after_discount_init);
//                dd($order_product);
                if ($product_exist == true){ // if product is exist
                    // update product price and quantity in product table
                    $product_exist->price                       = $order_product['item_price'];
                    $product_exist->discount                    = $order_product['item_discount'];
                    $product_exist->discount_type               = $discount_type_init;
                    $product_exist->discount_amount             = $discount_amount_init;
                    $product_exist->price_after_discount        = $item_price_after_discount_init;
                    $product_exist->quantity                    = $product_exist->quantity + $order_product['item_quantity'];
                    $product_exist->save();
                }else{
                    // create new product to product table
                    Product::create([
                        'code'                              => $order_product['item_code'],
                        'name'                              => $order_product['item_name'],
                        'price'                             => $order_product['item_price'],
                        'discount'                          => $order_product['item_discount'],
                        'discount_type'                     => $discount_type_init,
                        'discount_amount'                   => $discount_amount_init,
                        'price_after_discount'              => $item_price_after_discount_init,
                        'quantity'                          => $order_product['item_quantity'],
                        'sub_category_id'                   => $order_product['sub_category_id'],
                        'branch_id'                         => $request -> branch_id,
                        'user_id'                           => $user_id
                    ]);
                }
            }

//            if ($request -> supplier_discount_amount)
//            {
//                $supplier_discount_amount = $request -> supplier_discount_amount;
//            }

            /* Update Supplier balance after add total amount duo */
            $supplier_balance_after_add_total_amount_due = $supplier->balance + $request->total_amount_due ;
            $supplier->update(['balance' => $supplier_balance_after_add_total_amount_due ]);

            $this -> insertToSupplierTransaction($purchaseOrder,
                [
                    'total_amount'                              => $request->total_amount_due,
                    'supplier_balance'                          => $supplier_balance_after_add_total_amount_due,
                    'details'                                   => 'فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number,
                    'amount_paid'                               => $amount_paid,
                    'amount_paid_bank'                          => $amount_paid_bank,
                    'amount_due'                                => $request->amount_due,
                    'transaction_date'                          => $request->invoice_date,
                    'transaction_type'                          => 'debit',
                    'debit'                                     => $request->total_amount_due,
                    'user_id'                                   => $user_id,
                    'supplier_id'                               => $request->supplier_id,
                ]
            );

            if ($total_amounts_paid_plus_bank > 0)
            {
                /* Update Supplier balance after subtract total amount paid */
                $supplier_balance_after_subtract_total_amounts_paid = $supplier->balance - $total_amounts_paid_plus_bank ;
                $supplier->update(['balance' => $supplier_balance_after_subtract_total_amounts_paid ]);

                SupplierTransaction::create([
                    'total_amount'              => $total_amounts_paid_plus_bank,
                    'supplier_balance'          => $supplier_balance_after_subtract_total_amounts_paid,
                    'details'                   => 'سداد فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number, // details
                    'amount_paid'               => $amount_paid,
                    'amount_paid_bank'          => $amount_paid_bank,
                    'amount_due'                => $request->amount_due,
                    'transaction_date'          => $request->invoice_date,
                    'transaction_type'          => 'credit',
                    'credit'                    => $total_amounts_paid_plus_bank,
                    'user_id'                   => $user_id,
                    'supplier_id'               => $request->supplier_id,
                ]);

                /* insert into statement table */
                $amount_paid = $request->amount_paid ?? null;
                $amount_paid_bank = $request->amount_paid_bank ?? null;
//                $total_vat = $request -> total_vat  ?? null;

                if ($amount_paid_bank > 0)
                {
                    /* insert record under field custody administration network */
                    Statement::create([
                        'custody_administration_network'    => $amount_paid_bank,
                        'notes'                             => 'عهدة من الادارة',
                        'branch_id'                         =>  $request -> branch_id,
                    ]);
                }

                /* Record Transaction On Statement Table */
                $this -> insertToStatement(
                    $purchaseOrder, // relatedModel
                    [
                        'expenses_cash'                 =>  $amount_paid,
                        'expenses_network'              =>  $amount_paid_bank,
                        'notes'                         =>  'فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number,
                        'branch_id'                     =>  $request -> branch_id,
                    ]
                );
            }
            // if exist supplier discount amount discount this from supplier balance
            if ($request->supplier_discount) {
                $supplier_discount_type = $request->supplier_discount_type;
                $supplier_discount = $request->supplier_discount;
                $total_amount_due = $request->total_amount_due;
                $supplier_discount_amount_or_percentage = $supplier_discount_type == 1 ? ($total_amount_due * $supplier_discount / 100) : $supplier_discount;
                /* Update Supplier balance after subtract supplier discount amount or percentage */
                $supplier_balance_after_subtract_supplier_discount_amount_or_percentage = $supplier->balance - $supplier_discount_amount_or_percentage ;
                $supplier->update(['balance' => $supplier_balance_after_subtract_supplier_discount_amount_or_percentage ]);
                /* Record Transaction On Supplier Transaction Table */
                $this -> insertToSupplierTransaction($purchaseOrder,
                    [
                        'supplier_discount_on_purchase_order'       => $supplier_discount_amount_or_percentage,
                        'total_amount'                              => $supplier_discount_amount_or_percentage,
                        'supplier_balance'                          => $supplier_balance_after_subtract_supplier_discount_amount_or_percentage,
                        'details'                                   => 'خصم المورد على فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number,
                        'transaction_date'                          => $request->invoice_date,
                        'transaction_type'                          => 'credit',
                        'credit'                                    => $supplier_discount_amount_or_percentage,
                        'user_id'                                   => $user_id,
                        'supplier_id'                               => $request->supplier_id,
                    ]
                );
            }

            /* Update Money Safe Amount */
            $purchaseOrder->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe - ($amount_paid)),
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);


            /* Update Bank Amount */
            $purchaseOrder->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank - ($amount_paid_bank)),
                'money_process_type' => 0,
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);


            return redirect() -> route('admin.purchaseOrders.show', $purchaseOrder->id) -> with('success', __('trans.purchase order added successfully'));

        }

    }

    public function show($id)
    {
        $purchase_order = PurchaseOrder::findOrFail($id);
        if ($purchase_order -> status == 'open')
            abort(404);
//            return redirect() -> route('admin.purchaseOrders.index', ['status' => 'open']);
        return view('admin.purchaseOrders.show_invoice', compact('purchase_order'));
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::findOrfail($id);
        if ($purchaseOrder -> status == 'close')
            abort(404);
        $categories = Category::select('id', 'name')->with('subCategories')->get() -> toJson();
        $categories_list = Category::select('id', 'name')->with('subCategories')->get();
        return view('admin.purchaseOrders.edit',compact('purchaseOrder', 'categories', 'categories_list'));
    }

    public function update(EditOpenPurchaseOrder $request, $id)
    {
        $user_id = Auth::user()->id;
        $purchaseOrder = PurchaseOrder::findOrFail($id);
//        $purchaseOrderData = $request ->except(['product_data']);
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
        $data_except_amounts = $request -> except(['amount_paid', 'amount_paid_bank']);
        $data_except_amount_paid_and_payment_methods = $request -> except(['amount_paid', 'amount_paid_bank', 'invoice_number', 'payment_method', 'payment_method_bank']);
        // if amount payment method request empty set payment method null
        $payment_method = $request -> payment_method ?? null;
        // if amount payment method bank request empty set payment method bank null
        $payment_method_bank = $request -> payment_method_bank ?? null;
        // if amount paid request empty set amount paid equal zero
        $amount_paid = $request->amount_paid ?? 0;
        // if amount paid bank request empty set amount paid bank equal zero
        $amount_paid_bank = $request->amount_paid_bank ?? 0;

        $total_amounts_paid_plus_bank = $amount_paid + $amount_paid_bank ;
        $order_products = $request->product_data;
        $supplier = Supplier::findOrFail($request->supplier_id);

        $sold_products_list = $purchaseOrder ->purchaseOrderProducts;
        $new_products_codes_array = Arr::pluck($order_products, 'item_code');

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
            $product_sold = PurchaseOrderProducts::where(['purchase_order_id' => $purchaseOrder -> id, 'item_code' => $new_order_product_code]) -> first();

            if ($product_sold) { // this item already sold // exist in open purchase order products table

                $sold_order_product_quantity = $sold_quantity_with_code[$new_order_product_code];
                if ($sold_order_product_quantity != $new_order_product_quantity) {

                    $product_exist = Product::where(['branch_id' => $request->branch_id, 'code' => $new_order_product_code])->first();
                    // check if new product already exist in products table
                    if ($product_exist) {
                        $product_exist->update([
                            'quantity' => $product_exist->quantity - $sold_order_product_quantity + $new_order_product_quantity
                        ]);
                    }

                }
            }else { // this item already not sold // not exist in open purchase order products table

                $product_exist = Product::where(['branch_id' => $request -> branch_id, 'code' => $new_order_product_code])->first();
                // check if new product already exist in products table
                if ($product_exist) {
                    $product_exist -> update([
                        'quantity' => $product_exist -> quantity + $new_order_product_quantity
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
                PurchaseOrderProducts::where(['purchase_order_id' => $purchaseOrder -> id, 'item_code' => $product_sold_code]) -> delete();
                $item_exist_in_products_table = Product::where(['branch_id' => $request -> branch_id, 'code' => $product_sold_code])->first();
                if ($item_exist_in_products_table) {
                    $item_exist_in_products_table -> update([
                        'quantity' => $item_exist_in_products_table -> quantity - $product_sold_quantity
                    ]);
                }
            }
        } // end foreach

        // delete all purchase order products
        $purchaseOrder -> purchaseOrderProducts () ->delete();
        // add new products to purchase order products table
        foreach ($order_products as $new_product) {
            $purchaseOrder -> purchaseOrderProducts() -> create($new_product);
        }
        // update purchase order data
        $init_data = [
            'amount_paid'           => $amount_paid,
            'amount_paid_bank'      => $amount_paid_bank,
            'payment_method'        => $payment_method,
            'payment_method_bank'   => $payment_method_bank
        ];
        $purchaseOrder -> update($data_except_amount_paid_and_payment_methods + $init_data);

        if ($request -> soft_save) {
            return redirect() -> back() -> with('success', __('trans.open purchase order edit successfully'));
        } else {
//            dd($request -> all());

            $purchaseOrder -> update(['status' => 'close']);
            /* Check if purchase order amount less than safe money amount redirect to link */
            $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;

            if ($final_amount_money_safe < $amount_paid){ // on mount in the safe is not enough redirect

                return redirect()->route('admin.purchaseOrders.index', ['status' => 'close'])->with('delete', __('trans.the amount in the safe is not enough'));

            }

            /* Check if purchase order amount less than safe money amount redirect to link */
            $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;

            if ($final_amount_bank < $amount_paid_bank){ // on mount in the bank is not enough redirect

                return redirect()->route('admin.purchaseOrders.index', ['status' => 'close'])->with('delete', __('trans.the amount in the bank is not enough'));

            }

            /* Check if purchase order amount less than safe money amount redirect to link */
            $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;

            if ($final_amount_bank < $amount_paid_bank){ // on mount in the bank is not enough redirect

                return redirect()->route('admin.purchaseOrders.index', ['status' => 'close'])->with('delete', __('trans.the amount in the bank is not enough'));

            }

            foreach ($order_products as $order_product) {
//                dd($order_product);
                // insert products to purchase order products table with relation
//                $purchaseOrder -> purchaseOrderProducts() -> create($order_product);

                // check product exist in product table
                $product_exist = Product::where(['branch_id' => $request -> branch_id, 'code' => $order_product['item_code']])->first();
                $discount_amount_or_percentage = $order_product['item_discount_type'] == 1 ? ( $order_product['item_price'] * $order_product['item_discount'] / 100): $order_product['item_discount'];
                $item_price_after_discount_init = $order_product['item_price'] - $discount_amount_or_percentage;
                $discount_type_init = empty($order_product['item_discount']) ? null : $order_product['item_discount_type'] ;
                $discount_amount_init = empty($order_product['item_discount']) ? null : $order_product['item_discount_amount'] ;
//                dd($item_price_after_discount_init);
//                dd($order_product);
                if ($product_exist == true){ // if product is exist
                    // update product price and quantity in product table
                    $product_exist->price                       = $order_product['item_price'];
                    $product_exist->discount                    = $order_product['item_discount'];
                    $product_exist->discount_type               = $discount_type_init;
                    $product_exist->discount_amount             = $discount_amount_init;
                    $product_exist->price_after_discount        = $item_price_after_discount_init;
                    $product_exist->quantity                    = $product_exist->quantity + $order_product['item_quantity'];
                    $product_exist->save();
                }else{
                    // create new product to product table
                    Product::create([
                        'code'                              => $order_product['item_code'],
                        'name'                              => $order_product['item_name'],
                        'price'                             => $order_product['item_price'],
                        'discount'                          => $order_product['item_discount'],
                        'discount_type'                     => $discount_type_init,
                        'discount_amount'                   => $discount_amount_init,
                        'price_after_discount'              => $item_price_after_discount_init,
                        'quantity'                          => $order_product['item_quantity'],
                        'sub_category_id'                   => $order_product['sub_category_id'],
                        'branch_id'                         => $request -> branch_id,
                        'user_id'                           => $user_id
                    ]);
                }
            }

            /* Update Supplier balance after add total amount duo */
            $supplier_balance_after_add_total_amount_due = $supplier->balance + $request->total_amount_due ;
            $supplier->update(['balance' => $supplier_balance_after_add_total_amount_due ]);

            $this -> insertToSupplierTransaction($purchaseOrder,
                [
                    'total_amount'                              => $request->total_amount_due,
                    'supplier_balance'                          => $supplier_balance_after_add_total_amount_due,
                    'details'                                   => 'فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number,
                    'amount_paid'                               => $amount_paid,
                    'amount_paid_bank'                          => $amount_paid_bank,
                    'amount_due'                                => $request->amount_due,
                    'transaction_date'                          => $request->invoice_date,
                    'transaction_type'                          => 'debit',
                    'debit'                                     => $request->total_amount_due,
                    'user_id'                                   => $user_id,
                    'supplier_id'                               => $request->supplier_id,
                ]
            );

            if ($total_amounts_paid_plus_bank > 0)
            {
                /* Update Supplier balance after subtract total amount paid */
                $supplier_balance_after_subtract_total_amounts_paid = $supplier->balance - $total_amounts_paid_plus_bank ;
                $supplier->update(['balance' => $supplier_balance_after_subtract_total_amounts_paid ]);

                SupplierTransaction::create([
                    'total_amount'              => $total_amounts_paid_plus_bank,
                    'supplier_balance'          => $supplier_balance_after_subtract_total_amounts_paid,
                    'details'                   => 'سداد فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number, // details
                    'amount_paid'               => $amount_paid,
                    'amount_paid_bank'          => $amount_paid_bank,
                    'amount_due'                => $request->amount_due,
                    'transaction_date'          => $request->invoice_date,
                    'transaction_type'          => 'credit',
                    'credit'                    => $total_amounts_paid_plus_bank,
                    'user_id'                   => $user_id,
                    'supplier_id'               => $request->supplier_id,
                ]);

                /* Record Transaction On Statement Table */
                $this -> insertToStatement(
                    $purchaseOrder, // relatedModel
                    [
                        'expenses_cash'                 =>  $amount_paid,
                        'expenses_network'              =>  $amount_paid_bank,
                        'notes'                         =>  'فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number,
                        'branch_id'                     =>  $request -> branch_id,
                    ]
                );
            }

            // if exist supplier discount amount discount this from supplier balance
            if ($request->supplier_discount) {
                $supplier_discount_type = $request->supplier_discount_type;
                $supplier_discount = $request->supplier_discount;
                $total_amount_due = $request->total_amount_due;
                $supplier_discount_amount_or_percentage = $supplier_discount_type == 1 ? ($total_amount_due * $supplier_discount / 100) : $supplier_discount;
                /* Update Supplier balance after subtract supplier discount amount or percentage */
                $supplier_balance_after_subtract_supplier_discount_amount_or_percentage = $supplier->balance - $supplier_discount_amount_or_percentage ;
                $supplier->update(['balance' => $supplier_balance_after_subtract_supplier_discount_amount_or_percentage ]);
                /* Record Transaction On Supplier Transaction Table */
                $this -> insertToSupplierTransaction($purchaseOrder,
                    [
                        'supplier_discount_on_purchase_order'       => $supplier_discount_amount_or_percentage,
                        'total_amount'                              => $supplier_discount_amount_or_percentage,
                        'supplier_balance'                          => $supplier_balance_after_subtract_supplier_discount_amount_or_percentage,
                        'details'                                   => 'خصم المورد على فاتورة مشتريات رقم / ' . $purchaseOrder -> invoice_number,
                        'transaction_date'                          => $request->invoice_date,
                        'transaction_type'                          => 'credit',
                        'credit'                                    => $supplier_discount_amount_or_percentage,
                        'user_id'                                   => $user_id,
                        'supplier_id'                               => $request->supplier_id,
                    ]
                );
            }

            /* Update Money Safe Amount */
            $purchaseOrder->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe - ($amount_paid)),
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);


            /* Update Bank Amount */
            $purchaseOrder->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank - ($amount_paid_bank)),
                'money_process_type' => 0,
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);

            /* insert into statement table */
            $amount_paid = $request->amount_paid ?? null;
            $amount_paid_bank = $request->amount_paid_bank ?? null;

            if ($amount_paid_bank > 0)
            {
                /* insert record under field custody administration network */
                Statement::create([
                    'custody_administration_network'    => $amount_paid_bank,
                    'notes'                             => 'عهدة من الادارة',
                    'branch_id'                         =>  $request -> branch_id,
                ]);
            }



            return redirect() -> route('admin.purchaseOrders.show', $purchaseOrder->id) -> with('success', __('trans.purchase order added successfully'));

        }
    }


    public function destroy($id)
    {
        PurchaseOrder::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.purchase order delete successfully'));
    }

    public function purchaseOrderProducts($purchase_order_id)
    {
        // show purchase orders products Page
        $purchaseOrder = PurchaseOrder::findOrFail($purchase_order_id); // show products for purchase order
        return view('admin.purchaseOrders.orderProducts', compact('purchaseOrder'));
    }

    public function search_product_code(Request $request)
    {
        if ($request -> ajax())
        {
            $keyword = $request -> keyword;

            if (!empty($keyword))
            {
                $result = ProductCode::where('name' , 'like', '%'.$keyword.'%')
                    ->orWhere('code' , 'like', '%'.$keyword.'%')
                    ->select('name', 'code')
                    ->get();
            }else
            {
                $result = ProductCode::select('name', 'code')->get();
            }
            return response()->json(['result' => $result], 200);
        }
    }

    public function check_amount_from_moneySafe_and_bank(Request $request)
    {
        if ($request -> ajax())
        {
            $branch_id = $request -> branch_id;
            $amount_paid = $request -> amount_paid;
            $amount_paid_bank = $request -> amount_paid_bank;
            $money_safe = '';
            $bank = '';

            if ($branch_id)
            {
                if ($amount_paid && $amount_paid > 0)
                {
                    /* Check if purchase order amount less than safe money amount redirect to link */
                    $money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
                }
                if ($amount_paid_bank && $amount_paid_bank > 0)
                {
                    /* Check if purchase order amount less than bank amount redirect to link */
                    $bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
                }
                $safe_money_amount = $money_safe ? $money_safe->final_amount : 0;
                $bank_amount = $bank ? $bank->final_amount : 0;
                return response()->json(['safe_money_amount' => $safe_money_amount, 'bank_amount' => $bank_amount], 200);

            } // end if branch selected return value
        } // end if ajax
    }

    public function tax_invoice(Request $request)
    {
//        dd('gg');
        $purchaseOrderId = $request -> purchase_order_id;
//        $data = [];
        $data['purchase_order'] = PurchaseOrder::findOrFail($purchaseOrderId);
        $mpdf = PDF::loadView('admin.purchaseOrders.tax_invoice', $data, [], [
            'margin_top' => 130,
            'margin_header' => 10,
            'margin_footer' => 20,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;
//         $mpdf->download($data['purchase_order']->invoice_number.'.pdf');
//        if ($request->download)
//        {
//            return $mpdf->download($data['purchase_order']->invoice_number.'.pdf');
//
//        }
         return $mpdf->stream($data['purchase_order']->invoice_number.'.pdf');
    }
}
