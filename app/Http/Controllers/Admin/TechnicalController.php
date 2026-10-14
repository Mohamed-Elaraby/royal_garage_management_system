<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TechnicalDatatable;
use App\Http\Requests\Technical\AddAndUpdateTechnicalRequest;
use App\Models\Branch;
use App\Models\Check;
use App\Models\SaleOrder;
use App\Models\SaleOrderProducts;
use App\Models\Technical;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TechnicalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-technicals')->only('index');
        $this->middleware('permission:create-technicals')->only('create');
        $this->middleware('permission:update-technicals')->only('edit');
        $this->middleware('permission:delete-technicals')->only('destroy');
    }

    public function index(TechnicalDatatable $technicalDatatable)
    {
        $branchName = '';
        if (request('branch_id')) {
            $branchName = Branch::findOrFail(request('branch_id'))->name; // Get [ branch name ] to send into view
        }
        return $technicalDatatable -> render('admin.technicals.index', compact('branchName'));
    }

    public function create()
    {
        $branches = Branch::pluck('name', 'id') -> toArray();
        return view('admin.technicals.create', compact('branches'));
    }

    public function store(AddAndUpdateTechnicalRequest $request)
    {
        Technical::create($request -> all());
        return redirect() -> route('admin.technicals.index') -> with('success', __('trans.technical added successfully'));
    }

    public function show($id)
    {
        $technical = Technical::findOrFail($id);
        return view('admin.technicals.show', compact('technical'));
    }

    public function edit($id)
    {
        $technical = Technical::findOrFail($id);
        $branches = Branch::pluck('name', 'id') -> toArray();
        return view('admin.technicals.edit',compact('technical', 'branches'));
    }

    public function update(AddAndUpdateTechnicalRequest $request, $id)
    {
        $technical = Technical::findOrFail($id);
        $technical->update($request -> all());
        return redirect() -> route('admin.technicals.index') -> with('success', __('trans.technical edit successfully'));
    }


    public function destroy($id)
    {
        Technical::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.technical delete successfully'));
    }

    public function technical_productivity(Request $request)
    {
        $technical_id = $request -> technical_id;
        $technical = Technical::findOrFail($technical_id);
        return view('admin.technicals.technical_productivity', compact('technical'));
    }

    public function get_technical_productivity_by_ajax(Request $request)
    {
        if ($request -> ajax())
        {
            $start_date = $request -> startDate;
            $end_date = $request -> endDate;
            $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];
            $technical_id = $request -> technical_id;

            $total = [];

            $callback = function ($query) use ($technical_id) {
                if ($technical_id)
                {
                    $query -> where('technical_id', $technical_id);
                }
            };
            // get all checks for spics technical
            $technical_checks = Check::whereBetween('created_at', $date_range)
                ->  whereHas('technicals', $callback)
                ->  with(['technicals'])
                ->  get(['id']);

            foreach ($technical_checks as $technical_check) {

                $technicals_count_for_checks = $technical_check -> technicals -> count();

                $totalSaleOrderProducts = SaleOrderProducts::whereHas('product', function ($query){
                    $query -> whereHas('subCategory', function ($query){
                        $query -> whereHas('category', function ($query){
                            $query -> where ('name', 'اجور اليد');
                        });
                    });
                })
                    ->whereHas('saleOrder', function ($query)use( $technical_check){
                        $query -> where('status', 'close');
                        $query -> where('check_id', $technical_check -> id);
                    })
                    ->sum('item_sub_total');
                $technical_amount_for_saleOrder = $totalSaleOrderProducts/$technicals_count_for_checks;
                array_push($total, $technical_amount_for_saleOrder);

            } // end foreach
            $total_amounts = array_sum($total); // calc total amounts inside array
            $hands_fees = number_format($total_amounts, 2); // total hands fees and format number
            return response()->json(['technical_checks' => $technical_checks, 'hands_fees' => $hands_fees] , 200);
        }
    }
}
