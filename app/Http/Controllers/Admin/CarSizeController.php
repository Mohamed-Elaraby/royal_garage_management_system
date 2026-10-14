<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CarSizeDatatable;
use App\Http\Requests\carSize\AddAndUpdateCarSizeRequest;
use App\Models\CarModel;
use App\Models\CarSize;
use App\Models\CarType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-carSize')->only('index');
        $this->middleware('permission:create-carSize')->only('create');
        $this->middleware('permission:update-carSize')->only('edit');
        $this->middleware('permission:delete-carSize')->only('destroy');
    }

    public function index(CarSizeDatatable $carSizeDatatable)
    {
        $carTypeName = '';
        $carModel = collect(CarModel::get()) ->toJson();
        if (request('car_type_id')){
            $carTypeName = CarType::findOrFail(request('car_type_id'))->name;
        }
        return $carSizeDatatable -> render('admin.carSize.index', compact('carModel', 'carTypeName'));
    }

    public function create(Request $request)
    {
        $car_type_id = $request -> get('car_type_id');
        $target_car_type = CarType::findOrFail($car_type_id);
        return view('admin.carSize.create', compact('target_car_type'));
    }

    public function store(AddAndUpdateCarSizeRequest $request)
    {
        carSize::create($request -> all());
        return redirect() -> route('admin.carSize.index') -> with('success', __('trans.car size added successfully'));
    }


    public function edit($id)
    {
        $carSize = carSize::findOrFail($id);
        return view('admin.carSize.edit',compact('carSize'));
    }

    public function update(AddAndUpdateCarSizeRequest $request, $id)
    {
        $carSize = carSize::findOrFail($id);
        $carSize->update($request -> all());
        return redirect() -> route('admin.carSize.index') -> with('success', __('trans.car size edit successfully'));
    }


    public function destroy($id)
    {
        carSize::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.car size delete successfully'));
    }

/*    public function getCarModelByAjax(Request $request)
    {
        if ($request -> ajax()) {
            $carSizeId = $request->carSizeId;
            $carModelsIds = car_size_model_relation::where('car_size_id', $carSizeId)->get()->pluck('car_model_id');
            return response()->json($carModelsIds, 200);
        }
    }

    public function saveCarModelByAjax(Request $request)
    {
        if ($request -> ajax()) {
            $carSizeId = $request->carSizeValue;
            $carModelIds = $request->carModelIds;
            $carSize = CarSize::findOrFail($carSizeId);
            $carSize -> carModels() -> sync($carModelIds);
        }
    }*/
}
