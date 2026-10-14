<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CarDevelopmentCodeDatatable;
use App\Http\Requests\carDevelopmentCode\AddAndUpdateCarDevelopmentCodeRequest;
use App\Models\CarDevelopmentCode;
use App\Models\CarSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarDevelopmentCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-carDevelopmentCode')->only('index');
        $this->middleware('permission:create-carDevelopmentCode')->only('create');
        $this->middleware('permission:update-carDevelopmentCode')->only('edit');
        $this->middleware('permission:delete-carDevelopmentCode')->only('destroy');
    }

    public function index(CarDevelopmentCodeDatatable $carDevelopmentCodeDatatable)
    {
        $carSizeName = '';
        if (request('car_size_id')){
            $carSizeName = CarSize::findOrFail(request('car_size_id'))->name;
        }
        return $carDevelopmentCodeDatatable ->render('admin.carDevelopmentCode.index', compact('carSizeName'));
    }

    public function create(Request $request)
    {
        $car_size_id = $request -> get('car_size_id');
        $target_car_size = CarSize::findOrFail($car_size_id);
        return view('admin.carDevelopmentCode.create', compact('target_car_size'));
    }

    public function store(AddAndUpdateCarDevelopmentCodeRequest $request)
    {
        CarDevelopmentCode::create($request -> all());
        return redirect() -> route('admin.carDevelopmentCode.index') -> with('success', __('trans.car development code added successfully'));
    }


    public function edit($id)
    {
        $carDevelopmentCode = CarDevelopmentCode::findOrFail($id);
        return view('admin.carDevelopmentCode.edit',compact('carDevelopmentCode'));
    }

    public function update(AddAndUpdateCarDevelopmentCodeRequest $request, $id)
    {
        $carDevelopmentCode = CarDevelopmentCode::findOrFail($id);
        $carDevelopmentCode->update($request -> all());
        return redirect() -> route('admin.carDevelopmentCode.index') -> with('success', __('trans.car development code edit successfully'));
    }


    public function destroy($id)
    {
        CarDevelopmentCode::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.car development code delete successfully'));
    }
}
