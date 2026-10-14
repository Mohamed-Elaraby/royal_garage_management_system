<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BranchDatatable;
use App\Http\Requests\branch\AddAndUpdateBranchRequest;
use App\Models\Branch;
use App\Models\Check;
use App\Models\CheckStatus;
use App\Models\Technical;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-branches')->only('index');
        $this->middleware('permission:create-branches')->only('create');
        $this->middleware('permission:update-branches')->only('edit');
        $this->middleware('permission:delete-branches')->only('destroy');
    }

    public function index(BranchDatatable $branchDatatable)
    {
        return $branchDatatable -> render('admin.branches.index');
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(AddAndUpdateBranchRequest $request)
    {
        Branch::create($request -> all());
        return redirect() -> route('admin.branches.index') -> with('success', __('trans.branch added successfully'));
    }

    public function show($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branches.show', compact('branch'));
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branches.edit',compact('branch'));
    }

    public function update(AddAndUpdateBranchRequest $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update($request -> all());
        return redirect() -> route('admin.branches.index') -> with('success', __('trans.branch edit successfully'));
    }


    public function destroy($id)
    {
        Branch::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.branch delete successfully'));
    }



}
