<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExpensesDatatable;
use App\Http\Requests\Expenses\AddAndUpdateExpensesRequest;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Expenses;
use App\Models\ExpensesType;
use App\Models\MoneySafe;
use App\Models\Statement;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-expenses')->only('index');
        $this->middleware('permission:create-expenses')->only('create');
        $this->middleware('permission:update-expenses')->only('edit');
        $this->middleware('permission:delete-expenses')->only('destroy');
    }

    public function index(ExpensesDatatable $expensesDatatable)
    {
        return $expensesDatatable ->render('admin.expenses.index');
    }

    public function create()
    {
        $expensesTypes = ExpensesType::pluck('name', 'id')->toArray();
        $branches = Branch::pluck('name', 'id')->toArray();
        return view('admin.expenses.create', compact( 'branches', 'expensesTypes'));
    }

    public function store(AddAndUpdateExpensesRequest $request)
    {
        $user_id = Auth::user()->id;

        /* Check if expenses amount less than safe money amount redirect to link */
        if ($request -> payment_method == 'كاش')
        {
            $last_amount = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount = $last_amount ? $last_amount->final_amount : 0;
            if ($final_amount == 0 || $final_amount < $request->amount){ // on mount in the safe is not enough redirect

                return redirect()->route('admin.expenses.index')->with('delete', __('trans.the amount in the safe is not enough'));

            }
        }else
        {
            $last_amount = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount = $last_amount ? $last_amount->final_amount : 0;
            if ($final_amount == 0 || $final_amount < $request->amount){ // on mount in the safe is not enough redirect

                return redirect()->route('admin.expenses.index')->with('delete', __('trans.the amount in the bank is not enough'));

            }
        }
//        dd($request->all());
        $expenses = Expenses::create($request->all() + ['user_id' => $user_id]);
        $expenses_note = $expenses -> notes ? ' [ ' .$expenses -> notes . ' ]' : '';

        if ($request -> payment_method == 'كاش')
        {
            /* Update Money Safe Amount */
            $expenses->moneySafes()->create([
                'amount_paid' => $request->amount,
                'final_amount' => ($final_amount - ($request->amount)),
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);

            /* insert into expenses table */
            $amount_paid = $request->amount ?? null;


            /* Record Transaction On Statement Table */
            $this -> insertToStatement(
                $expenses, // relatedModel
                [
                    'expenses_cash'                 =>  $amount_paid,
                    'notes'                         =>  $expenses -> expensesType -> name . $expenses_note,
                    'branch_id'                     =>  $request -> branch_id,
                ]
            );
        }else
        {
            /* Update Money Safe Amount */
            $expenses->banks()->create([
                'amount_paid' => $request->amount,
                'final_amount' => ($final_amount - ($request->amount)),
                'money_process_type' => 0,
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);

            /* insert into expenses table */
            $amount_paid = $request->amount ?? null;

            /* insert record under field custody administration network */
            Statement::create([
                'custody_administration_network'    => $amount_paid,
                'notes'                             => 'عهدة من الادارة',
                'branch_id'                         =>  $request -> branch_id,
            ]);

            /* Record Transaction On Statement Table */
            $this -> insertToStatement(
                $expenses, // relatedModel
                [
                    'expenses_network'              =>  $amount_paid,
                    'notes'                         =>  $expenses -> expensesType -> name . $expenses_note,
                    'branch_id'                     =>  $request -> branch_id,
                ]
            );
        }

        return redirect()->route('admin.expenses.index')->with('success', __('trans.expenses added successfully'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
//        $expense = Expenses::findOrFail($id);
////        $expensesType = ExpensesType::all();
//        $branches = Branch::pluck('name', 'id')->toArray();
//        return view('admin.expenses.edit', compact('expense', 'branches'));
    }

    public function update(AddAndUpdateExpensesRequest $request, $id)
    {
        //        dd($request->all());
//        Expenses::findOrFail($id)->update($request->all());
//        return redirect()->route('admin.expenses.index')->with('success', __('trans.expenses edit successfully'));
    }

    public function destroy($id)
    {
        $expense = Expenses::findOrFail($id);
        $user_id = Auth::user()->id;

        if ($expense -> payment_method == 'كاش'){
            $last_amount = MoneySafe::where(['branch_id' => $expense -> branch_id])->get()->last();
            $final_amount = $last_amount ? $last_amount->final_amount : 0;
            /* Update Money Safe Amount */
            $expense->moneySafes()->create([
                'amount_paid' => $expense->amount,
                'final_amount' => ($final_amount + ($expense->amount)),
                'processType' => 2,
                'user_id' => $user_id,
                'branch_id' => $expense -> branch_id,
            ]);
        }else
        {
            $last_amount = Bank::where(['branch_id' => $expense -> branch_id])->get()->last();
            $final_amount = $last_amount ? $last_amount->final_amount : 0;
            /* Update Bank Amount */
            $expense->banks()->create([
                'amount_paid' => $expense->amount,
                'final_amount' => ($final_amount + ($expense->amount)),
                'processType' => 2,
                'money_process_type' => 1, // addition
                'user_id' => $user_id,
                'branch_id' => $expense -> branch_id,
            ]);
        }
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', __('trans.expenses delete successfully'));
    }
}
