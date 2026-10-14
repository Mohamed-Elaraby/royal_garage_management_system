<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\JobTitleDatatable;
use App\Http\Requests\jobTitle\AddAndUpdateJobTitleRequest;
use App\Models\JobTitle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobTitleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-jobTitle')->only('index');
        $this->middleware('permission:create-jobTitle')->only('create');
        $this->middleware('permission:update-jobTitle')->only('edit');
        $this->middleware('permission:delete-jobTitle')->only('destroy');
    }

    public function index(JobTitleDatatable $jobTitleDatatable)
    {
        return $jobTitleDatatable -> render('admin.jobTitle.index');
    }

    public function create()
    {
        return view('admin.jobTitle.create');
    }

    public function store(AddAndUpdateJobTitleRequest $request)
    {
        JobTitle::create($request -> all());
        return redirect() -> route('admin.jobTitle.index') -> with('success', __('trans.job title added successfully'));
    }


    public function edit($id)
    {
        $jobTitle = JobTitle::findOrFail($id);
        return view('admin.jobTitle.edit',compact('jobTitle'));
    }

    public function update(AddAndUpdateJobTitleRequest $request, $id)
    {
        $jobTitle = JobTitle::findOrFail($id);
        $jobTitle->update($request -> all());
        return redirect() -> route('admin.jobTitle.index') -> with('success', __('trans.job title edit successfully'));
    }


    public function destroy($id)
    {
        JobTitle::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.job title delete successfully'));
    }
}
