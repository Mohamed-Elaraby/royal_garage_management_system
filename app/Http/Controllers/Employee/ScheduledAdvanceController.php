<?php

namespace App\Http\Controllers\Employee;

use App\Models\ScheduledAdvance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduledAdvanceController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
//        $advance_id = $request->advance_id;
        $scheduled_advances = ScheduledAdvance::where('advance_id', $id)->get();

        dd($scheduled_advances);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getSchedulingDetailsByAjax(Request $request)
    {
        if ($request -> ajax())
        {
            $advance_id = $request -> advance_id;
            $ScheduledAdvances = ScheduledAdvance::where('advance_id', $advance_id) -> get();
            return response()->json(['scheduled_advances' => $ScheduledAdvances], 200);
        }
    }

    public function edit_status_advance(Request $request)
    {
        if ($request -> ajax())
        {
            dd($request -> ajax());
        }
    }
}
