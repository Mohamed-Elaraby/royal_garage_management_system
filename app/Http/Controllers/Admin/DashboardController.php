<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Check;
use App\Models\CheckStatus;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
//        if (Auth::user()->hasRole('owner')) {
            $branchName = '';
            if ($request->branch_id)
            {
                if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer']))
                    abort(403);
                $branchName = Branch::findOrFail($request->branch_id)->name; // Get [ branch name ] to send into view
            }
            // ======================= Global Variables ======================= //
            $checkStatus = CheckStatus::select('id', 'name', 'color')->get();


            // ======================= data in day ======================= //
            $checksLogAtToday = Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer'])?
                Check::whereDate('created_at', Carbon::today())->select('check_number', 'branch_id', 'car_id', 'created_at')->get():
                Check::whereDate('created_at', Carbon::today()) -> selectBranchUser() -> select('check_number', 'branch_id', 'car_id', 'created_at')->get();
            if ($request->branch_id)
            {
                $checksLogAtToday = Check::where('branch_id', $request->branch_id)->whereDate('created_at', Carbon::today())->select('check_number', 'branch_id', 'car_id', 'created_at')->get();
            }

//            dd($checksLogAtToday -> branch -> name);

            $totalCheckCountInDay = Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer'])?
                Check::whereDate('updated_at', Carbon::today()) -> get() ->count():
                Check::whereDate('updated_at', Carbon::today()) -> selectBranchUser() -> get() ->count();

            if ($request->branch_id){
                $totalCheckCountInDay = Check::where('branch_id', $request->branch_id) -> whereDate('updated_at', Carbon::today()) -> get() ->count();
            }

            $dataDay = [];
            foreach ($checkStatus as $status) {
                $checksCountInDay = Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer'])?
                    Check::where('check_status_id', $status -> id)->whereDate('updated_at', Carbon::today())->get()->count():
                    Check::where('check_status_id', $status -> id)->whereDate('updated_at', Carbon::today()) -> selectBranchUser() ->get()->count();
                if ($request->branch_id){
                    $checksCountInDay = Check::where('branch_id', $request->branch_id) -> where('check_status_id', $status -> id)->whereDate('updated_at', Carbon::today())->get()->count();
                }
                $totalCheckCountInDay !=0 ? $percentageInDay = number_format($checksCountInDay/$totalCheckCountInDay*100,2):$percentageInDay = 0;

                $dataDay[] = [
                    'check_status_id'       => $status->id,
                    'check_status_name'     => $status->name,
                    'check_status_color'    => $status->color,
                    'checks_count'          => $checksCountInDay,
                    'data_percentage'       => $percentageInDay
                ];
            }
            // ======================= data in month ======================= //

            $latestClientsInMonth = Client::whereMonth('created_at', Carbon::now()->month) -> whereYear('created_at', Carbon::now()->year) -> take(8) -> latest() -> get();
            $allClientsCountAtToday= Client::whereDate('created_at', Carbon::today()) -> get()->count();
            $totalCheckCountInMonth = Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer'])?
                Check::whereMonth('updated_at', Carbon::now()->month) -> whereYear('updated_at', Carbon::now()->year) -> get() ->count():
                Check::whereMonth('updated_at', Carbon::now()->month) -> whereYear('updated_at', Carbon::now()->year) -> selectBranchUser() -> get() ->count();
            if ($request->branch_id){
                $totalCheckCountInMonth = Check::where('branch_id', $request->branch_id) ->whereMonth('updated_at', Carbon::now()->month) -> whereYear('updated_at', Carbon::now()->year) -> get() ->count();
            }
            $dataMonth = [];
            foreach ($checkStatus as $status) {
                $checksCountInMonth = Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer'])?
                    Check::where('check_status_id', $status -> id)->whereMonth('updated_at', Carbon::now()->month) -> whereYear('updated_at', Carbon::now()->year)-> get()->count():
                    Check::where('check_status_id', $status -> id)->whereMonth('updated_at', Carbon::now()->month) -> whereYear('updated_at', Carbon::now()->year)-> selectBranchUser() -> get()->count();
                if ($request->branch_id){
                    $checksCountInMonth = Check::where('branch_id', $request->branch_id) -> where('check_status_id', $status -> id)->whereMonth('updated_at', Carbon::now()->month) ->whereYear('updated_at', Carbon::now()->year)-> get()->count();
                }
                $totalCheckCountInMonth !=0 ? $percentageInMonth = number_format($checksCountInMonth/$totalCheckCountInMonth*100,2):$percentageInMonth = 0;
                $dataMonth[] = [
                    'check_status_id'       => $status->id,
                    'check_status_name'     => $status->name,
                    'check_status_color'    => $status->color,
                    'checks_count'          => $checksCountInMonth,
                    'data_percentage'       => $percentageInMonth
                ];
            }
            //dd($totalCheckCountInDay);
            return view('admin.dashboard', compact('dataMonth', 'totalCheckCountInMonth', 'latestClientsInMonth', 'allClientsCountAtToday', 'dataDay', 'totalCheckCountInDay', 'checksLogAtToday', 'branchName'));
//        }
//        return redirect()->route('admin.check.index');
    }
}
