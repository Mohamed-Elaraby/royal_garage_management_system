<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDatatable;
use App\Http\Requests\Category\AddAndUpdateCategoryRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-categories')->only('index');
        $this->middleware('permission:create-categories')->only('create');
        $this->middleware('permission:update-categories')->only('edit');
        $this->middleware('permission:delete-categories')->only('destroy');
    }

    public function index(CategoryDatatable $categoryDatatable)
    {
        return $categoryDatatable -> render('admin.categories.index');
    }

    public function create()
    {
        $main_categories = Category::pluck('name', 'id')->toArray();
        return view('admin.categories.create', compact('main_categories'));
    }

    public function store(AddAndUpdateCategoryRequest $request)
    {
        Category::create($request -> all());
        return redirect() -> route('admin.categories.index') -> with('success', __('trans.category added successfully'));
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit',compact('category'));
    }

    public function update(AddAndUpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request -> all());
        return redirect() -> route('admin.categories.index') -> with('success', __('trans.category edit successfully'));
    }


    public function destroy($id)
    {
        Category::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.category delete successfully'));
    }
}
