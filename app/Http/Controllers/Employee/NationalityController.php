<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\NationalityDatatable;
use App\Models\Branch;
use App\Models\Nationality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NationalityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-nationalities')->only('index');
        $this->middleware('permission:create-nationalities')->only('create');
        $this->middleware('permission:update-nationalities')->only('edit');
        $this->middleware('permission:delete-nationalities')->only('destroy');
    }

    public function index(NationalityDatatable $nationalityDatatable)
    {
        return $nationalityDatatable -> render('employee.nationalities.index');
    }

    public function create()
    {
        return view('employee.nationalities.create');
    }

    public function store(Request $request)
    {
        Nationality::create($request -> all());
        return redirect() -> route('employee.nationalities.index') -> with('success', __('trans.nationality added successfully'));
    }

    public function show($id)
    {
        $nationality = Nationality::findOrFail($id);
        return view('employee.nationalities.show', compact('nationality'));
    }

    public function edit($id)
    {
        $nationality = Nationality::findOrFail($id);
        return view('employee.nationalities.edit',compact('nationality'));
    }

    public function update(Request $request, $id)
    {
        $nationality = Nationality::findOrFail($id);
        $nationality->update($request -> all());
        return redirect() -> route('employee.nationalities.index') -> with('success', __('trans.nationality edit successfully'));
    }

    public function destroy($id)
    {
        Nationality::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.nationality delete successfully'));
    }
}
