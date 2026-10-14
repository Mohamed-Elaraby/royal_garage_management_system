<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MoneySafeLogDatatable;
use App\Http\Requests\MoneySafeOperations\moneySafeOperationsRequest;
use App\Models\Branch;
use App\Models\MoneySafe;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MoneySafeController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-moneySafe')->only('index');
        $this->middleware('permission:create-moneySafe')->only('operations');
        $this->middleware('permission:update-moneySafe')->only('edit');
        $this->middleware('permission:delete-moneySafe')->only('destroy');
    }

    public function index($branch_id)
    {
        $branch = Branch::findOrFail($branch_id);
        $moneySafe = MoneySafe::where('branch_id', $branch_id)->select('final_amount', 'branch_id')->orderBy('id', 'desc')->first();
        return view('admin.moneySafe.index', compact('moneySafe', 'branch'));
    }

    public function operations($branch_id)
    {
        $branch = Branch::findOrFail($branch_id);
        return view('admin.moneySafe.create', compact('branch'));
    }

    public function store(moneySafeOperationsRequest $request)
    {
        $user_id = Auth::user()->id;

        $last_amount = MoneySafe::where('branch_id', $request -> branch_id) -> get()->last(); // get last amount
        $final_amount = $last_amount ? $last_amount->final_amount : 0; // if last amount not empty set final amount else final amount equal zero


        if ($request->processType == 0){ // on withdrawn

            if ($final_amount == 0 || $final_amount < $request->amount_paid){ // on mount in the safe is not enough redirect

                $redirect = redirect()->route('admin.moneySafe.index', $request->branch_id)->with('delete', __('trans.the amount in the safe is not enough'));

            }else { // on withdrawn money

                $moneySafe = MoneySafe::create([
                    'amount_paid'           => $request->amount_paid,
                    'final_amount'          => $final_amount - $request->amount_paid,
                    'notes'                 => $request->notes,
                    'processType'           => $request->processType,
                    'user_id'               => $user_id,
                    'branch_id'             => $request->branch_id,
                ]);

                $this -> insertToStatement(
                    $moneySafe, // relatedModel
                    [
                        'cash_to_administration'            =>  $request -> amount_paid,
                        'notes'                             =>  $moneySafe -> notes ? 'نقدى الى الادارة [ ' . $moneySafe -> notes . ' ]' : 'نقدى الى الادارة ',
                        'branch_id'                         =>  $request -> branch_id,
                    ]
                );

                $redirect = redirect()->route('admin.moneySafe.index', $request->branch_id)->with('success', __('trans.the amount has been withdrawn successfully'));

            }
        }else{ // on deposited money

            $moneySafe = MoneySafe::create([
                'amount_paid'           => $request->amount_paid,
                'final_amount'          => $final_amount + $request->amount_paid,
                'notes'                 => $request->notes,
                'processType'           => $request->processType,
                'user_id'               => $user_id,
                'branch_id'             => $request->branch_id,
            ]);

            $amount_paid = $request->amount_paid;

//            if ($request -> payment_method == 'cash')
//            {
//                $amount_paid_bank = null;
//            }
//            else
//            {
//                $amount_paid_bank = $request->amount_paid;
//                $amount_paid = null;
//            }

            /* Record Transaction On Statement Table */
            $this -> insertToStatement(
                $moneySafe, // relatedModel
                [
                    'custody_administration_cash'       =>  $amount_paid,
                    'notes'                             =>  'عهدة من الادارة',
                    'branch_id'                         =>  $request -> branch_id,
                ]
            );

            $redirect = redirect()->route('admin.moneySafe.index', $request->branch_id)->with('success', __('trans.the amount has been deposited successfully'));
        }

        return $redirect;
    }

    public function money_safe_log(MoneySafeLogDatatable $moneySafeLogDatatable, Request $request)
    {
        $branch_id = $request -> branch_id;
        $branch = Branch::findOrFail($branch_id);
//        $moneySafe = MoneySafe::where('branch_id', $branch_id)->get();
        return $moneySafeLogDatatable -> render('admin.moneySafe.log', compact('branch'));
//        return view('admin.moneySafe.index', compact('moneySafe', 'branch'));
    }
}
