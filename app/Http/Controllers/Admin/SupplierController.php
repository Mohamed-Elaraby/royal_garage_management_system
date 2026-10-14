<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SupplierDatatable;
use App\DataTables\SupplierTransactionDatatable;
use App\Http\Requests\Supplier\AddAndUpdateSupplierRequest;
use App\Models\Supplier;
use App\Models\SupplierTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-suppliers')->only('index');
        $this->middleware('permission:create-suppliers')->only('create');
        $this->middleware('permission:update-suppliers')->only('edit');
        $this->middleware('permission:delete-suppliers')->only('destroy');
    }

    public function index(SupplierDatatable $supplierDatatable)
    {
        return $supplierDatatable -> render('admin.suppliers.index');
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(AddAndUpdateSupplierRequest $request)
    {
        Supplier::create($request -> all());
        return redirect() -> route('admin.suppliers.index') -> with('success', __('trans.supplier added successfully'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit',compact('supplier'));
    }

    public function update(AddAndUpdateSupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request -> all());
        return redirect() -> route('admin.suppliers.index') -> with('success', __('trans.supplier edit successfully'));
    }


    public function destroy($id)
    {
        Supplier::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.supplier delete successfully'));
    }

//    public function supplierTransactions($supplier_id)
//    {
//        // show supplier transactions Page
//        $supplier = Supplier::findOrFail($supplier_id);
//        $transactions = SupplierTransaction::where('supplier_id', $supplier_id)->orderByDesc('id')->paginate(5);
//        return view('admin.suppliers.transactions', compact('supplier', 'transactions'));
//    }

    public function supplierTransactions($supplier_id, SupplierTransactionDatatable $supplierTransactionDatatable,Request $request)
    {
//        dd($request -> supplier_id);
        // show supplier transactions Page
        $supplier = Supplier::findOrFail($supplier_id);
//        $transactions = ClientTransaction::where('supplier_id', $supplier_id)->orderByDesc('id')->paginate(5);
        return $supplierTransactionDatatable -> render('admin.suppliers.transactions', compact('supplier'));
    }
}
