<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductCodeDatatable;
use App\Http\Requests\ProductCode\AddProductCodeRequest;
use App\Http\Requests\ProductCode\UpdateProductCodeRequest;
use App\Models\ProductCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-productCodes')->only('index');
        $this->middleware('permission:create-productCodes')->only('create');
        $this->middleware('permission:update-productCodes')->only('edit');
        $this->middleware('permission:delete-productCodes')->only('destroy');
    }

    public function index(ProductCodeDatatable $productCodeDatatable)
    {
        return $productCodeDatatable -> render('admin.productCodes.index');
    }

    public function create()
    {
        return view('admin.productCodes.create');
    }

    public function store(AddProductCodeRequest $request)
    {
        foreach ($request -> data as $data) {
            ProductCode::create($data);
        }

//        return redirect() -> back() -> with('success', __('trans.product code added successfully'));
        return redirect() -> route('admin.productCodes.index') -> with('success', __('trans.product code added successfully'));
    }

    public function show($id)
    {
        $productCode = ProductCode::findOrFail($id);
        return view('admin.productCodes.show', compact('productCode'));
    }

    public function edit($id)
    {
        $productCode = ProductCode::findOrFail($id);
        return view('admin.productCodes.edit',compact('productCode'));
    }

    public function update(UpdateProductCodeRequest $request, $id)
    {
        $productCode = ProductCode::findOrFail($id);
        $productCode->update($request -> all());
        return redirect() -> route('admin.productCodes.index') -> with('success', __('trans.product code edit successfully'));
    }


    public function destroy($id)
    {
        ProductCode::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.product code delete successfully'));
    }
}
