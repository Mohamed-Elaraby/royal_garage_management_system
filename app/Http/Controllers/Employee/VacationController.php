<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\VacationsDatatable;
use App\Interfaces\moneySafe\balance;
use App\Interfaces\vacation\Calculate;
use App\Interfaces\salary\Calculate as calculateSalary;
use App\Models\Employee;
use App\Models\Vacation;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VacationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-vacations')->only('index');
        $this->middleware('permission:create-vacations')->only('create');
        $this->middleware('permission:update-vacations')->only('edit');
        $this->middleware('permission:delete-vacations')->only('destroy');
    }

    public function index(VacationsDatatable $vacationsDatatable, Request $request)
    {
        $employeeName = '';
        if ($request->employee_id) // Get all check with [ check status id ] from request url
            $employeeName = Employee::findOrFail($request->employee_id)->name; // Get [ check status name ] to send into view
        return $vacationsDatatable -> render('employee.vacations.index', compact('employeeName'));
    }

    public function create(Request $request)
    {
        $employee_id = $request->employee_id;
        $employee = Employee::findOrFail($employee_id);
        return view('employee.vacations.create', compact('employee'));
    }

    public function store(Request $request)
    {

        $user_id = Auth::user()->id;
        $employee_id = $request -> employee_id;

        $branch_id = Employee::findOrFail($employee_id)->branch_id;

        $start_vacation = $request -> start_vacation;
        $end_vacation   = $request -> end_vacation;
        $vacation_calculate = new Calculate();
        $total_vacation_days = $vacation_calculate -> totalDays($start_vacation, $end_vacation);

        $calculate_salary = new calculateSalary();
        $total_salary = $calculate_salary -> totalSalary($employee_id);

        $salary_per_day = $calculate_salary -> salaryPerDay($total_salary);

        $discount_amount = $vacation_calculate -> discountAmount($total_vacation_days, $salary_per_day);

        $vacation = Vacation::create($request -> all() + ['user_id' => $user_id, 'total_days' => $total_vacation_days]);

        if ($request->type == 'تخصم من الراتب'){
            $vacation->update(['status' => 'لم تخصم حتى الان', 'discount_amount' => $discount_amount]);

//            $money_safe = new balance();
//            $check_balance = $money_safe -> checkBalance($discount_amount, $branch_id);
//            if ($check_balance)
//            {
//                return redirect() -> route('employee.advances.index') -> with('delete', __('trans.the amount in the safe is not enough'));
//            }else
//            {
//                $money_safe -> decreaseBalance($vacation, $discount_amount, $branch_id);
//            }
        }

        return redirect() -> route('employee.vacations.index') -> with('success', __('trans.vacation added successfully'));
    }

    public function show($id)
    {
        $vacation = Vacation::findOrFail($id);
        return view('employee.vacations.show', compact('vacation'));
    }

    public function edit($id)
    {
        $vacation = Vacation::findOrFail($id);
        return view('employee.vacations.edit',compact('vacation'));
    }

    public function update(Request $request, $id)
    {
//        dd($request -> all());
        $vacation = Vacation::findOrFail($id);
        $vacation->update($request -> all());
        return redirect() -> route('employee.vacations.index') -> with('success', __('trans.vacation edit successfully'));
    }

    public function destroy($id)
    {
        Vacation::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.vacation delete successfully'));
    }
}
