<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExpensesTypeDatatable;
use App\Models\ExpensesType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpensesTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-expensesTypes')->only('index');
        $this->middleware('permission:create-expensesTypes')->only('create');
        $this->middleware('permission:update-expensesTypes')->only('edit');
        $this->middleware('permission:delete-expensesTypes')->only('destroy');
    }

    public function index(ExpensesTypeDatatable $expensesTypeDatatable)
    {
        return $expensesTypeDatatable ->render('admin.expensesTypes.index');
    }

    public function create()
    {

        return view('admin.expensesTypes.create');
    }

    public function store(Request $request)
    {

        ExpensesType::create($request->all());
        return redirect()->route('admin.expensesTypes.index')->with('success', __('trans.expenses type added successfully'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $expensesType = ExpensesType::findOrFail($id);
        return view('admin.expensesTypes.edit', compact('expensesType'));
    }

    public function update(Request $request, $id)
    {
        ExpensesType::findOrFail($id)->update($request->all());
        return redirect()->route('admin.expensesTypes.index')->with('success', __('trans.expenses type edit successfully'));
    }

    public function destroy($id)
    {
        ExpensesType::findOrFail($id)->delete();
        return redirect()->route('admin.expensesTypes.index')->with('success', __('trans.expenses type delete successfully'));
    }
}
