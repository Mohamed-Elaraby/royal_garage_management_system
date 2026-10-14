<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EngineerDatatable;
use App\Models\Branch;
use App\Models\Engineer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EngineerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-engineers')->only('index');
        $this->middleware('permission:create-engineers')->only('create');
        $this->middleware('permission:update-engineers')->only('edit');
        $this->middleware('permission:delete-engineers')->only('destroy');
    }

    public function index(EngineerDatatable $engineerDatatable)
    {
        $branchName = '';
        if (request('branch_id')) {
            $branchName = Branch::findOrFail(request('branch_id')) -> name; // Get [ branch name ] to send into view
        }
        return $engineerDatatable -> render('admin.engineers.index', compact('branchName'));
    }

    public function create()
    {
        $branches = Branch::pluck('name', 'id') -> toArray();
        return view('admin.engineers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        Engineer::create($request -> all());
        return redirect() -> route('admin.engineers.index') -> with('success', __('trans.engineer added successfully'));
    }

    public function show($id)
    {
        $engineer = Engineer::findOrFail($id);
        return view('admin.engineers.show', compact('engineer'));
    }

    public function edit($id)
    {
        $engineer = Engineer::findOrFail($id);
        $branches = Branch::pluck('name', 'id') -> toArray();
        return view('admin.engineers.edit',compact('engineer', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $engineer = Engineer::findOrFail($id);
        $engineer->update($request -> all());
        return redirect() -> route('admin.engineers.index') -> with('success', __('trans.engineer edit successfully'));
    }


    public function destroy($id)
    {
        Engineer::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.engineer delete successfully'));
    }
}
