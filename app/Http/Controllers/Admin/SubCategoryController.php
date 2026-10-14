<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubCategoriesDatatable;
use App\Http\Requests\SubCategory\AddAndUpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-subCategories')->only('index');
        $this->middleware('permission:create-subCategories')->only('create');
        $this->middleware('permission:update-subCategories')->only('edit');
        $this->middleware('permission:delete-subCategories')->only('destroy');
    }

    public function index(SubCategoriesDatatable $subCategoriesDatatable)
    {
        return $subCategoriesDatatable -> render('admin.subCategories.index');
    }

    public function create()
    {
        $main_categories = Category::pluck('name', 'id')->toArray();
        return view('admin.subCategories.create', compact('main_categories'));
    }

    public function store(AddAndUpdateSubCategoryRequest $request)
    {
        SubCategories::create($request -> all());
        return redirect() -> route('admin.subCategories.index') -> with('success', __('trans.sub category added successfully'));
    }

    public function show($id)
    {
//        $category = SubCategories::findOrFail($id);
//        return view('admin.subCategories.show', compact('category'));
    }

    public function edit($id)
    {
        $subCategory = SubCategories::findOrFail($id);
        return view('admin.subCategories.edit',compact('subCategory'));
    }

    public function update(AddAndUpdateSubCategoryRequest $request, $id)
    {
        $subCategory = SubCategories::findOrFail($id);
        $subCategory->update($request -> all());
        return redirect() -> route('admin.subCategories.index') -> with('success', __('trans.sub category edit successfully'));
    }


    public function destroy($id)
    {
        SubCategories::findOrFail($id)->delete();
        return redirect()->back()->with('delete', __('trans.sub category delete successfully'));
    }
}
