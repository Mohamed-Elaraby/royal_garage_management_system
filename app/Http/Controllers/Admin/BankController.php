<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BankLogDatatable;
use App\Http\Requests\bankOperations\bankOperationsRequest;
use App\Models\Bank;
use App\Models\Branch;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-bank')->only('index');
        $this->middleware('permission:create-bank')->only('operations');
        $this->middleware('permission:update-bank')->only('edit');
        $this->middleware('permission:delete-bank')->only('destroy');
    }

    public function index($branch_id)
    {
        if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'deputy_manager']))
        {
            if (Auth::user()->branch_id != $branch_id)
                abort(403);
        }
        $branch = Branch::findOrFail($branch_id);
        if (Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'deputy_manager']))
        {
            $bank = Bank::where('branch_id', $branch_id)->select('final_amount', 'branch_id')->orderBy('id', 'desc')->first();
            $bank = $bank ? $bank  -> final_amount : '';
        }
        else
        {

            $bank_addition = Bank::whereDate('created_at', Carbon::today())
                -> where('branch_id', $branch_id)
                -> where('money_process_type', 1)
                -> whereNotNull('money_process_type')
                ->sum('amount_paid');
            $bank_subtract = Bank::whereDate('created_at', Carbon::today())
                -> where('branch_id', $branch_id)
                -> where('money_process_type', 0)
                -> whereNotNull('money_process_type')
                ->sum('amount_paid');
            $bank= $bank_addition - $bank_subtract;


//                $q -> whereNotNull('advance_id');

//            dd($bank_addition,$bank_subtract);
        }
//        $bank = Bank::whereDate('created_at', Carbon::today()) -> where('branch_id', $branch_id)->sum('amount_paid');

        return view('admin.bank.index', compact('bank', 'branch'));
    }

    public function operations($branch_id)
    {
        $branch = Branch::findOrFail($branch_id);
        return view('admin.bank.create', compact('branch'));
    }

    public function store(bankOperationsRequest $request)
    {
        $user_id = Auth::user()->id;

        $last_amount = Bank::where('branch_id', $request -> branch_id) -> get()->last(); // get last amount
        $final_amount = $last_amount ? $last_amount->final_amount : 0; // if last amount not empty set final amount else final amount equal zero


        if ($request->processType == 0){ // on withdrawn

            if ($final_amount == 0 || $final_amount < $request->amount_paid){ // on mount in the safe is not enough redirect

                $redirect = redirect()->route('admin.bank.index', $request->branch_id)->with('delete', __('trans.the amount in the safe is not enough'));

            }else { // on withdrawn money

                Bank::create([
                    'amount_paid'           => $request->amount_paid,
                    'final_amount'          => $final_amount - $request->amount_paid,
                    'money_process_type'    => 0,
                    'notes'                 => $request->notes,
                    'processType'           => $request->processType,
                    'user_id'               => $user_id,
                    'branch_id'             => $request->branch_id,
                ]);

                $redirect = redirect()->route('admin.bank.index', $request->branch_id)->with('success', __('trans.the amount has been withdrawn successfully'));

            }
        }else{ // on deposited money

            $bank = Bank::create([
                'amount_paid'           => $request->amount_paid,
                'final_amount'          => $final_amount + $request->amount_paid,
                'money_process_type'    => 1,
                'notes'                 => $request->notes,
                'processType'           => $request->processType,
                'user_id'               => $user_id,
                'branch_id'             => $request->branch_id,
            ]);
            $amount_paid = $request->amount_paid;
            /* Record Transaction On Statement Table */
            $this -> insertToStatement(
                $bank, // relatedModel
                [
                    'custody_administration_network'        =>  $amount_paid,
                    'notes'                                 =>  'عهدة من الادارة',
                    'branch_id'                             =>  $request -> branch_id,
                ]
            );

            $redirect = redirect()->route('admin.bank.index', $request->branch_id)->with('success', __('trans.the amount has been deposited successfully'));
        }

        return $redirect;
    }

    public function bank_log(BankLogDatatable $bankLogDatatable, Request $request)
    {
        $branch_id = $request -> branch_id;
        $branch = Branch::findOrFail($branch_id);
//        $moneySafe = MoneySafe::where('branch_id', $branch_id)->get();
        return $bankLogDatatable -> render('admin.bank.log', compact('branch'));
//        return view('admin.moneySafe.index', compact('moneySafe', 'branch'));
    }
}
