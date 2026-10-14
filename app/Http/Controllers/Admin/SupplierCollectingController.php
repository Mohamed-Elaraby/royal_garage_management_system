<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SupplierCollectingDatatable;
use App\Http\Requests\supplierCollecting\AddAndUpdateSupplierCollectingRequest;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\MoneySafe;
use App\Models\Supplier;
use App\Models\SupplierCollecting;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SupplierCollectingController extends Controller
{
    use HelperTrait ;

    public function __construct()
    {
        $this->middleware('permission:read-supplierCollecting')->only('index');
        $this->middleware('permission:create-supplierCollecting')->only('create');
        $this->middleware('permission:update-supplierCollecting')->only('edit');
        $this->middleware('permission:delete-supplierCollecting')->only('destroy');
    }


    public function index(SupplierCollectingDatatable $supplierCollectingDatatable)
    {
        return  $supplierCollectingDatatable -> render('admin.supplierCollecting.index');
    }

    public function create(Request $request)
    {
        $supplier = Supplier::findOrFail($request->supplier_id);
        $suppliers = Supplier::pluck('name', 'id')->toArray();
        $branches = Branch::pluck('name', 'id')->toArray();
        return view('admin.supplierCollecting.create', compact( 'supplier', 'suppliers', 'branches'));
    }

    public function store(AddAndUpdateSupplierCollectingRequest $request)
    {

//        dd($request->all());
        $user_id = Auth::user()->id;
        $amount_paid = $request->amount_paid ?? 0;
        $amount_paid_bank = $request->amount_paid_bank ?? 0;
        $total_amounts_paid = $amount_paid + $amount_paid_bank;
        $receipt_number = SupplierCollecting::all()->last() ? SupplierCollecting::all()->last()->id + 1 : 1;
        $supplierCollecting = SupplierCollecting::create($request->all() + ['receipt_number' => $receipt_number, 'user_id' => $user_id]);
        $collecting_id = $supplierCollecting -> id;

        if ($request -> amount_paid)
        {
            /* Update Money Safe Amount */
            $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;
            $supplierCollecting->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe + ($amount_paid)),
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);
        }


        if ($request -> amount_paid_bank)
        {
            /* Check if amount paid bank less than bank amount redirect to link */
            $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;

            /* Update Bank Amount */
            $supplierCollecting->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank + ($amount_paid_bank)),
                'money_process_type' => 1,
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);
        }

        /* Record Transaction On Client Transaction Table */
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier_balance_after_add_total_amounts_paid = $supplier->balance + $total_amounts_paid ;
        $supplier->update(['balance' => $supplier_balance_after_add_total_amounts_paid ]);

        $this -> insertToSupplierTransaction(
            $supplierCollecting, // relatedModel
            [
                'total_amount'                  => $total_amounts_paid,
                'supplier_balance'              => $supplier_balance_after_add_total_amounts_paid,
                'details'                       => 'سند قبض رقم / ' . $supplierCollecting -> receipt_number,
                'amount_paid'                   => $amount_paid,
                'amount_paid_bank'              => $amount_paid_bank,
                'transaction_date'              => $request->collecting_date,
                'transaction_type'              => 'credit',
                'credit'                        => $total_amounts_paid,
                'user_id'                       => $user_id,
                'supplier_id'                   => $request->supplier_id,
            ]
        );

        /* Record Transaction On Statement Table */
        $amount_paid = $request->amount_paid ?? null;
        $amount_paid_bank = $request->amount_paid_bank ?? null;
        $this -> insertToStatement(
            $supplierCollecting, // relatedModel
            [
                'imports_cash'                  =>  $amount_paid,
                'imports_network'               =>  $amount_paid_bank,
                'notes'                         =>  ' سند قبض من المورد ' . $supplierCollecting -> supplier -> name,
                'branch_id'                     =>  $request -> branch_id,
            ]
        );

        return redirect()->route('admin.supplierCollecting.show', $collecting_id)->with('success', __('trans.supplier collecting added successfully'));
    }

    public function show($id)
    {
        $collecting_data = SupplierCollecting::findOrFail($id);
        return view('admin.supplierCollecting.receipt', compact('collecting_data'));
    }

    public function edit($id)
    {
//        $supplierCollecting = SupplierCollecting::findOrFail($id);
//        $suppliers = Supplier::all();
//        return view('admin.supplierCollecting.editSupplierCollecting', compact('supplierCollecting', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
//        SupplierCollecting::findOrFail($id)->update($request->all());
//        return redirect()->route('admin.supplierCollecting.index')->with('success', 'Payment Edit Successfully');
    }

    public function destroy(Request $request)
    {
        //
    }
}
