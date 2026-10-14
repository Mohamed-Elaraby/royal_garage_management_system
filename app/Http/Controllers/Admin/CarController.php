<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CarDatatable;
use App\Http\Requests\Car\AddCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarDevelopmentCode;
use App\Models\CarEngine;
use App\Models\CarModel;
use App\Models\CarSize;
use App\Models\CarType;
use App\Models\Check;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-cars')->only('index');
        $this->middleware('permission:create-cars')->only('create');
        $this->middleware('permission:update-cars')->only('edit');
        $this->middleware('permission:delete-cars')->only('destroy');
    }

    public function index(CarDatatable $carDatatable,Request $request)
    {
        $clientName = '';
        if ($request -> client_id){
            $clientName = Client::findOrFail($request -> client_id)->name;
        }
        return $carDatatable -> render('admin.cars.index', compact('clientName'));
    }

    public function create()
    {
        $carTypes = CarType::pluck('name', 'id')-> toArray();
        $carModels = CarModel::orderBy('name', 'DESC') -> pluck('name', 'id') -> toArray();
        return view('admin.cars.create', compact('carTypes', 'carModels'));
    }

    public function store(AddCarRequest $request)
    {
        Car::create($request -> all());
        return redirect() -> route('admin.cars.index', ['client_id' => $request -> client_id]) -> with('success', __('trans.car added successfully'));
    }


    public function edit($id)
    {
        $car = Car::findOrFail($id);
        $carTypes = CarType::pluck('name', 'id') -> toArray();
        $carSizes = CarSize::where('car_type_id', $car -> car_type_id) -> get();
        $carEngines = CarEngine::where('car_size_id', $car -> car_size_id) -> get();
        $carDevelopmentCodes = CarDevelopmentCode::where('car_size_id', $car -> car_size_id) -> get();
        $carModels = CarModel::orderBy('name', 'DESC') -> pluck('name', 'id') -> toArray();
        return view('admin.cars.edit',compact('car', 'carTypes', 'carModels', 'carSizes', 'carEngines', 'carDevelopmentCodes'));
    }

    public function update(UpdateCarRequest $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->update($request -> all());
        return redirect() -> route('admin.cars.index') -> with('success', __('trans.car edit successfully'));
    }


    public function destroy($id)
    {
        Car::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.car delete successfully'));
    }

    // Custom Function getCarSizesByAjax
    public function getCarSizesByAjax(Request $request)
    {
        if ($request -> ajax())
        {
            $car_type_id = $request -> car_type_id;
            $carSizes = CarSize::where('car_type_id', $car_type_id)->select('id', 'name')->get();
            return response() -> json($carSizes, 200);
        }
    }

    // Custom Function getCarDevCodeAndEnginesByAjax
    public function getCarDevCodeAndEnginesByAjax(Request $request)
    {
        if ($request -> ajax())
        {
            $car_size_id = $request -> car_size_id;
            $carEngines = CarEngine::where('car_size_id', $car_size_id)->select('id', 'name')->get();
            $carDevelopmentCodes = CarDevelopmentCode::where('car_size_id', $car_size_id)->select('id', 'name')->get();
            return response() -> json(['carEngines' => $carEngines, 'carDevelopmentCodes' => $carDevelopmentCodes], 200);
        }
    }

    public function transfer_car_ownership_view($car_id)
    {
        $car = Car::findOrFail($car_id);
        return view('admin.cars.transfer_car_ownership', compact('car'));
    }

    public function transfer_car_ownership(Request $request, $car_id)
    {
        $car = Car::findOrFail($car_id);
        $car -> update([
            'client_id' => $request -> client_id
        ]);
        return redirect() -> route('admin.cars.index') -> with('success', __('trans.transfer car ownership successfully'));
    }

    public function dataAjax(Request $request)
    {
//        dd($request->all());
        $search = $request->q;
        $the_car_owner_id = $request -> the_car_owner_id;

        if ($search == '')
        {
            $data = Client::where('id', '!=', $the_car_owner_id) -> select("id","name")->limit(5)->get();
        }else{
            $data = Client::where('id', '!=', $the_car_owner_id) -> select("id","name") ->where('name','LIKE',"%$search%") ->get();
        }

        return response()->json($data);
 }
}
