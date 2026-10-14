<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CarEngineDatatable;
use App\Http\Requests\carEngine\AddAndUpdateCarEngineRequest;
use App\Models\CarEngine;
use App\Models\CarSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarEngineController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-carEngine')->only('index');
        $this->middleware('permission:create-carEngine')->only('create');
        $this->middleware('permission:update-carEngine')->only('edit');
        $this->middleware('permission:delete-carEngine')->only('destroy');
    }

    public function index(CarEngineDatatable $carEngineDatatable)
    {
        $carSizeName = '';
        if (request('car_size_id')){
            $carSizeName = CarSize::findOrFail(request('car_size_id'))->name;
        }

        return $carEngineDatatable -> render('admin.carEngine.index', compact('carSizeName'));
    }

    public function create(Request $request)
    {
        $car_size_id = $request -> get('car_size_id');
        $target_car_size = CarSize::findOrFail($car_size_id);
        return view('admin.carEngine.create', compact('target_car_size'));
    }

    public function store(AddAndUpdateCarEngineRequest $request)
    {
        CarEngine::create($request -> all());
        return redirect() -> route('admin.carEngine.index') -> with('success', __('trans.car engine added successfully'));
    }


    public function edit($id)
    {
        $carEngine = CarEngine::findOrFail($id);
        return view('admin.carEngine.edit',compact('carEngine'));
    }

    public function update(AddAndUpdateCarEngineRequest $request, $id)
    {
        $carEngine = CarEngine::findOrFail($id);
        $carEngine->update($request -> all());
        return redirect() -> route('admin.carEngine.index') -> with('success', __('trans.car engine edit successfully'));
    }


    public function destroy($id)
    {
        CarEngine::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.car engine delete successfully'));
    }
}
