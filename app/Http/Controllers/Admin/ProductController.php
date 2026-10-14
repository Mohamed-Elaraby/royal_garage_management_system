<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDatatable;
use App\Http\Requests\Product\AddAndUpdateProductRequest;
use App\Models\Branch;
use App\Models\internalTransfer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-products')->only('index');
        $this->middleware('permission:create-products')->only('create');
        $this->middleware('permission:update-products')->only('edit');
        $this->middleware('permission:delete-products')->only('destroy');
    }

    public function index(ProductDatatable $productDatatable)
    {
        return $productDatatable -> render('admin.products.index');
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        Product::create($request -> all());
        return redirect() -> route('admin.products.index') -> with('success', __('trans.product added successfully'));
    }

    public function show($id)
    {
//        $product = Product::findOrFail($id);
//        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit',compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request -> all());
        return redirect() -> route('admin.products.index') -> with('success', __('trans.product edit successfully'));
    }

    public function destroy($id)
    {
        Product::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.product delete successfully'));
    }


}
