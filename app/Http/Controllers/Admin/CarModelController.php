<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CarModelDatatable;
use App\Http\Requests\carModel\AddAndUpdateCarModelRequest;
use App\Models\CarModel;
use App\Models\CarSize;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-carModel')->only('index');
        $this->middleware('permission:create-carModel')->only('create');
        $this->middleware('permission:update-carModel')->only('edit');
        $this->middleware('permission:delete-carModel')->only('destroy');
    }

    public function index(CarModelDatatable $carModelDatatable)
    {
        return $carModelDatatable -> render('admin.carModel.index');
    }

    public function create()
    {
        return view('admin.carModel.create');
    }

    public function store(AddAndUpdateCarModelRequest $request)
    {
        carModel::create($request -> all());
        return redirect() -> route('admin.carModel.index') -> with('success', __('trans.car model added successfully'));
    }


    public function edit($id)
    {
        $carModel = carModel::findOrFail($id);
        return view('admin.carModel.edit',compact('carModel'));
    }

    public function update(AddAndUpdateCarModelRequest $request, $id)
    {
        $carModel = carModel::findOrFail($id);
        $carModel->update($request -> all());
        return redirect() -> route('admin.carModel.index') -> with('success', __('trans.car model edit successfully'));
    }


    public function destroy($id)
    {
        carModel::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.car model delete successfully'));
    }
}
