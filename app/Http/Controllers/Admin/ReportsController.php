<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Expenses;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SaleOrder;
use App\Models\SaleOrderProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-reports')->only('index');
    }

    public function index()
    {
        $branches = Branch::all();
        return view('admin.reports.index', compact('branches'));
    }

    public function getDataSearch(Request $request)
    {
        if ($request -> ajax())
        {
            $start_date = $request -> startDate;
            $end_date = $request -> endDate;
            $branch_id = $request -> branch_id;
//            dd($start_date, $end_date, $branch_id);
            $total_purchases = PurchaseOrder::query()
                -> where(function ($query)use ($start_date,$end_date){
                        if ($start_date && $end_date)
                        {
                            $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];
                            $query -> whereBetween('created_at', $date_range);
                        }
                })
                -> where(function ($query) use ($branch_id){
                    if ($branch_id)
                    {
                        $query -> where('branch_id', $branch_id);
                    }
                })
                -> get() -> sum('total_amount_due');

            $total_sales = SaleOrder::query()
                -> where(function ($query)use ($start_date,$end_date){
                    if ($start_date && $end_date)
                    {
                        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];
                        $query -> whereBetween('created_at', $date_range);
                    }
                })
                -> where(function ($query) use ($branch_id){
                    if ($branch_id)
                    {
                        $query -> where('branch_id', $branch_id);
                    }
                })
                -> get() -> sum('total_amount_due');

            $total_expenses = Expenses::query()
                -> where(function ($query)use ($start_date,$end_date){
                    if ($start_date && $end_date)
                    {
                        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];
                        $query -> whereBetween('created_at', $date_range);
                    }
                })
                -> where(function ($query) use ($branch_id){
                    if ($branch_id)
                    {
                        $query -> where('branch_id', $branch_id);
                    }
                })
                -> get() -> sum('amount');

            $total_vat = SaleOrder::query()
                -> where(function ($query)use ($start_date,$end_date){
                    if ($start_date && $end_date)
                    {
                        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];
                        $query -> whereBetween('created_at', $date_range);
                    }
                })
                -> where(function ($query) use ($branch_id){
                    if ($branch_id)
                    {
                        $query -> where('branch_id', $branch_id);
                    }
                })
                -> get() -> sum('total_vat');
            $total = $total_sales - $total_purchases - $total_expenses - $total_vat;


            return response()->json([
                'total_purchases'       => $total_purchases,
                'total_sales'           => $total_sales,
                'total_expenses'        => $total_expenses,
                'total_vat'             => $total_vat,
                'total'                 => $total,
//                'Profit_ratio'          => $Profit_ratio,
                ], 200);

        }
    }
}
