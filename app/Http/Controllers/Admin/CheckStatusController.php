<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CheckStatusDatatable;
use App\Http\Requests\CheckStatus\AddAndUpdateCheckStatusRequest;
use App\Models\CheckStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-checkStatus')->only('index');
        $this->middleware('permission:create-checkStatus')->only('create');
        $this->middleware('permission:update-checkStatus')->only('edit');
        $this->middleware('permission:delete-checkStatus')->only('destroy');
    }

    public function index(CheckStatusDatatable $checkStatusDatatable)
    {
        return $checkStatusDatatable -> render('admin.checkStatus.index');
    }

    public function create()
    {
        return view('admin.checkStatus.create');
    }

    public function store(AddAndUpdateCheckStatusRequest $request)
    {
//        dd($request -> all());
        CheckStatus::create($request -> all());
        return redirect() -> route('admin.checkStatus.index') -> with('success', __('trans.checkStatus added successfully'));
    }


    public function edit($id)
    {
        $checkStatus = CheckStatus::findOrFail($id);
        return view('admin.checkStatus.edit',compact('checkStatus'));
    }

    public function update(AddAndUpdateCheckStatusRequest $request, $id)
    {
        $checkStatus = CheckStatus::findOrFail($id);
        $checkStatus->update($request -> all());
        return redirect() -> route('admin.checkStatus.index') -> with('success', __('trans.checkStatus edit successfully'));
    }


    public function destroy($id)
    {
        CheckStatus::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.checkStatus delete successfully'));
    }
}
