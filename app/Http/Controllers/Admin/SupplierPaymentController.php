<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SupplierPaymentDatatable;
use App\Http\Requests\supplierPayment\AddAndUpdateSupplierPaymentRequest;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\MoneySafe;
use App\Models\Statement;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SupplierPaymentController extends Controller
{
    use HelperTrait ;
    public function __construct()
    {
        $this->middleware('permission:read-supplierPayments')->only('index');
        $this->middleware('permission:create-supplierPayments')->only('create');
        $this->middleware('permission:update-supplierPayments')->only('edit');
        $this->middleware('permission:delete-supplierPayments')->only('destroy');
    }


    public function index(SupplierPaymentDatatable $supplierPaymentDatatable)
    {
        return  $supplierPaymentDatatable -> render('admin.supplierPayments.index');
    }

    public function create(Request $request)
    {
        $supplier = Supplier::findOrFail($request->supplier_id);
        $suppliers = Supplier::pluck('name', 'id')->toArray();
        $branches = Branch::pluck('name', 'id')->toArray();
        return view('admin.supplierPayments.create', compact( 'supplier', 'suppliers', 'branches'));
    }

    public function store(AddAndUpdateSupplierPaymentRequest $request)
    {
        $user_id = Auth::user()->id;
        $amount_paid = $request->amount_paid ?? 0;
        $amount_paid_bank = $request->amount_paid_bank ?? 0;
        $total_amounts_paid = $amount_paid + $amount_paid_bank;
        $receipt_number = SupplierPayment::all()->last() ? SupplierPayment::all()->last()->id + 1 : 1;

        /* Check if amount paid less than safe money amount redirect to link */
        $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
        $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;

        if ($final_amount_money_safe < $amount_paid){ // on amount in the safe is not enough redirect

            return redirect()->route('admin.supplierPayments.index')->with('delete', __('trans.the amount in the safe is not enough'));

        }

        /* Check if amount paid bank less than bank amount redirect to link */
        $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
        $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;

        if ($final_amount_bank < $amount_paid_bank){ // on amount in bank is not enough redirect

            return redirect()->route('admin.supplierPayments.index')->with('delete', __('trans.the amount in the bank is not enough'));

        }

        $supplierPayment = SupplierPayment::create($request->all() + ['receipt_number' => $receipt_number, 'user_id' => $user_id]);
        $payment_id = $supplierPayment -> id;

        /* Update Supplier balance after add total amounts paid */
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier_balance_after_subtract_total_amounts_paid = $supplier->balance - $total_amounts_paid ;
        $supplier->update(['balance' => $supplier_balance_after_subtract_total_amounts_paid ]);

        /* Record Transaction On Client Transaction Table */
        $this -> insertToSupplierTransaction($supplierPayment,
            [
                'total_amount'                              => $total_amounts_paid,
                'supplier_balance'                          => $supplier_balance_after_subtract_total_amounts_paid,
                'details'                                   => 'سند صرف رقم / ' . $supplierPayment -> receipt_number,
                'amount_paid'                               => $amount_paid,
                'amount_paid_bank'                          => $amount_paid_bank,
                'transaction_date'                          => $request->payment_date,
                'transaction_type'                          => 'debit',
                'debit'                                     => $total_amounts_paid,
                'user_id'                                   => $user_id,
                'supplier_id'                               => $request->supplier_id,
            ]
        );

        if ($request -> amount_paid)
        {
            /* Update Money Safe Amount */
            $supplierPayment->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe - ($amount_paid)),
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);
        }

        if ($request -> amount_paid_bank)
        {
            /* Update Bank Amount */
            $supplierPayment->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank - ($amount_paid_bank)),
                'money_process_type' => 0,
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);

            /* insert record under field custody administration network */
            Statement::create([
                'custody_administration_network'    => $amount_paid_bank,
                'notes'                             => 'عهدة من الادارة',
                'branch_id'                         =>  $request -> branch_id,
            ]);
        }


        /* Record Transaction On Statement Table */
        $amount_paid = $request->amount_paid ?? null;
        $amount_paid_bank = $request->amount_paid_bank ?? null;

        $this -> insertToStatement(
            $supplierPayment, // relatedModel
            [
                'expenses_cash'                 =>  $amount_paid,
                'expenses_network'              =>  $amount_paid_bank,
                'notes'                         =>  ' سند صرف الى المورد ' . $supplierPayment -> supplier -> name,
                'branch_id'                     =>  $request -> branch_id,
            ]
        );
        return redirect()->route('admin.supplierPayments.show', $payment_id)->with('success', __('trans.supplier payment added successfully'));
    }

    public function show($id)
    {
        $payment_data = SupplierPayment::findOrFail($id);
        return view('admin.supplierPayments.receipt', compact('payment_data'));
    }

    public function edit($id)
    {
//        $supplierPayment = SupplierPayment::findOrFail($id);
//        $suppliers = Supplier::all();
//        return view('admin.supplierPayments.editSupplierPayments', compact('supplierPayment', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
//        SupplierPayment::findOrFail($id)->update($request->all());
//        return redirect()->route('admin.supplierPayments.index')->with('success', 'Payment Edit Successfully');
    }

    public function destroy(Request $request)
    {
        //
    }
}
