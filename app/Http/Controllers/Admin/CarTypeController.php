<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CarTypeDatatable;
use App\Http\Requests\carType\AddAndUpdateCarTypeRequest;
use App\Models\CarModel;
use App\Models\CarType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-carType')->only('index');
        $this->middleware('permission:create-carType')->only('create');
        $this->middleware('permission:update-carType')->only('edit');
        $this->middleware('permission:delete-carType')->only('destroy');
    }

    public function index(CarTypeDatatable $carTypeDatatable)
    {
        return $carTypeDatatable -> render('admin.carType.index');
    }

    public function create()
    {
        return view('admin.carType.create');
    }

    public function store(AddAndUpdateCarTypeRequest $request)
    {
        CarType::create($request -> all());
        return redirect() -> route('admin.carType.index') -> with('success', __('trans.car type added successfully'));
    }


    public function edit($id)
    {
        $carType = CarType::findOrFail($id);
        return view('admin.carType.edit',compact('carType'));
    }

    public function update(AddAndUpdateCarTypeRequest $request, $id)
    {
        $carType = CarType::findOrFail($id);
        $carType->update($request -> all());
        return redirect() -> route('admin.carType.index') -> with('success', __('trans.car type edit successfully'));
    }


    public function destroy($id)
    {
        CarType::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.car type delete successfully'));
    }

    // Custom Function
    public function getAllCarModels($car_type_id)
    {
        $carModels = CarModel::where('car_type_id',$car_type_id)->get();
        return ;
    }

    // Custom Function
    public function getAllCarSizes($car_type_id)
    {
        dd($car_type_id);
    }

    // Custom Function
    public function getAllCarEngines($car_type_id)
    {
        dd($car_type_id);
    }
}
