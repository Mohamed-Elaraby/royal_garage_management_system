<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\SalaryDatatable;
use App\Interfaces\moneySafe\balance;
use App\Interfaces\bank\balance as bankBalance;
use \App\Interfaces\reward\Make as makeReward;
use \App\Interfaces\discount\Make as makeDiscount;
use App\Interfaces\advance\Make;
use App\Interfaces\salary\Calculate;
use App\Interfaces\vacation\Calculate as makeVacation;
use App\Models\Advance;
use App\Models\Car;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\EmployeeSalaryLog;
use App\Models\Images;
use App\Models\MoneySafe;
use App\Models\Reward;
use App\Models\SalaryYears;
use App\Models\ScheduledAdvance;
use App\Models\Statement;
use App\Models\Vacation;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-salaries')->only('index');
        $this->middleware('permission:create-salaries')->only('create');
        $this->middleware('permission:update-salaries')->only('edit');
        $this->middleware('permission:delete-salaries')->only('destroy');
    }

    public function index(SalaryDatatable $salaryDatatable)
    {
        return $salaryDatatable -> render('employee.salaries.index');
    }

    public function show($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function salaryDetails($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $salary_years = SalaryYears::with('')->pluck('year', 'id')->toArray();
        $years = [];
        foreach ($salary_years as $year)
        {
            $employee_salary_log = EmployeeSalaryLog::where('employee_id', $employee_id)->whereYear('salary_month', $year)->get()->toArray();
//            dd($employee_salary_log);
            $years [$year] = $employee_salary_log ;
        }
        $years = collect($years)->toArray();
//        dd($years);
        return view('employee.salaries.details', compact('employee', 'salary_years', 'years'));
    }

    public function salaryReceipt($employee_id)
    {

        $previous_month = Carbon::now()->subMonth()->format('m');
        $current_month = Carbon::now()->month;
        $previous_year = Carbon::now()->subYear()->format('Y');
        $current_year = Carbon::now()->year;

        $employee = Employee::findOrFail($employee_id);
        $salary = new Calculate();
        $total_salary = $salary->totalSalary($employee_id);

        $advance = new Make();
        $advance_list = $advance -> getAdvanceFromPreviousMonth($employee_id);
        $totalAdvance = $advance_list -> sum('amount');
        $schedule_advance = $advance -> getScheduleAdvanceFromPreviousMonth($employee_id);

        $rewards = new makeReward();
        $direct_rewards = $rewards -> getRewardFromPreviousMonth($employee_id);
        $totalRewards = $direct_rewards -> sum('amount');
        $to_salary_rewards = $rewards -> getRewardToSalaryFromPreviousMonth($employee_id);

        $discounts = new makeDiscount();
        $discounts_list = $discounts -> getDiscountFromPreviousMonth($employee_id);
        $totalDiscounts = $discounts_list -> sum('amount');

        $vacation = new makeVacation();
        $paid_vacations = $vacation -> getPaidVacationFromPreviousMonth($employee_id);
        $vacationsDeductedFromTheSalary = $vacation -> getVacationsDeductedFromTheSalaryFromPreviousMonth($employee_id);
        $content_allowed = false;

        $salaryPaid = EmployeeSalaryLog::where('employee_id', $employee_id)->whereYear('updated_at', $current_year) -> whereMonth('updated_at', $current_month)->first();
//        dd($salaryPaid);
        if (!$salaryPaid)
        {
            $content_allowed = true;
        }
        return view('employee.salaries.receipt', compact(
            'employee',
            'total_salary',
            'advance_list',
            'totalAdvance',
            'schedule_advance',
            'direct_rewards',
            'totalRewards',
            'to_salary_rewards',
            'discounts_list',
            'totalDiscounts',
            'paid_vacations',
            'vacationsDeductedFromTheSalary',
            'content_allowed'
        ));
    }

    public function registerToEmployeeLog(Request $request, $employee_id)
    {
        $branch_id = Employee::findOrFail($employee_id)->branch_id;
        $salaryRequest = $request -> except(['scheduledAdvance',
            'scheduledReward',
            'total_advances',
            'total_rewards',
            'select_reward_amount',
            'select_vacations_deducted_amount',
//            'select_normal_advance_amount',
            'select_advance_amount',
            'select_discount_amount',
            'salary_month'
            ]);
        $salary_month = Carbon::now()->subMonth()->toDateString();
        $salary_month_year = Carbon::parse($salary_month) -> format('Y-m');

        $total_advances = $request -> total_advances + $request -> scheduledAdvance ;
        $total_rewards = $request -> total_rewards + $request -> scheduledReward ;

        $total_advances_rewards = ['total_advances' => $total_advances, 'total_rewards' => $total_rewards];

        if ($request -> payment_method === 'كاش')
        {
            $moneySafe = new balance();
            $moneySafeHaveBalance = $moneySafe -> checkBalance($request -> final_salary, $branch_id );
            if ($moneySafeHaveBalance)
            {
                return redirect() -> route('employee.employees.index') -> with('delete', __('trans.the amount in the safe is not enough'));
            }else
            {
                $registerToEmployeeLog = EmployeeSalaryLog::create($total_advances_rewards + ['salary_month' => $salary_month] + $salaryRequest);
                $moneySafe ->decreaseBalance($registerToEmployeeLog, $request -> final_salary, $branch_id);

                $this -> insertToStatement(
                        $registerToEmployeeLog, // relatedModel
                            [
                            'advances_and_salaries_cash'        =>  $request -> final_salary,
                            'notes'                             =>   'راتب ' . $registerToEmployeeLog -> employee -> name . ' شهر ' . $salary_month_year,
                            'branch_id'                         =>  $branch_id,
                            ]
                    );
            }
        }else
        {
            $bank = new bankBalance();
            $bankHaveBalance = $bank -> checkBalance($request -> final_salary, $branch_id );
            if ($bankHaveBalance)
            {
                return redirect() -> route('employee.employees.index') -> with('delete', __('trans.the amount in the bank is not enough'));
            }else
            {
                $registerToEmployeeLog = EmployeeSalaryLog::create($total_advances_rewards + ['salary_month' => $salary_month] + $salaryRequest);
                $bank ->decreaseBalance($registerToEmployeeLog, $request -> final_salary, $branch_id);
                /* insert record under field custody administration network */
                Statement::create([
                    'custody_administration_network'    => $request -> final_salary,
                    'notes'                             => 'عهدة من الادارة',
                    'branch_id'                         =>  $branch_id,
                ]);
                /* Record Transaction On Statement Table */
                $this -> insertToStatement(
                    $registerToEmployeeLog, // relatedModel
                    [
                        'advances_and_salaries_network'     =>  $request -> final_salary,
                        'notes'                             =>  'راتب ' . $registerToEmployeeLog -> employee -> name . ' شهر ' . $salary_month_year,
                        'branch_id'                         =>  $branch_id,
                    ]
                );
            }
        }

        $current_salary_year = Carbon::parse($registerToEmployeeLog -> salary_month)->year ;
        $previous_salary_month = Carbon::parse($registerToEmployeeLog -> salary_month)->month ;
        $salary_years_list = SalaryYears::pluck('year')->toArray();
        if (!in_array($current_salary_year, $salary_years_list))
            SalaryYears::create(['year' => $current_salary_year]);

        if ($request -> select_advance_amount)
        {
            $ids = $request -> select_advance_amount;
            ScheduledAdvance::whereIn('id', $ids) -> update(['status' => 'مسددة']);
        }

        if ($request -> select_reward_amount)
        {
            $ids = $request -> select_reward_amount;
//            dd($ids);
            Reward::whereIn('id', $ids) -> update(['status' => 'اضيفت الى الراتب']);
        }

        if ($request -> select_discount_amount)
        {
            $ids = $request -> select_discount_amount;
            Discount::whereIn('id', $ids) -> update(['status' => 'تم الخصم']);
        }

        if ($request -> select_vacations_deducted_amount)
        {
            $ids = $request -> select_vacations_deducted_amount;
            Vacation::whereIn('id', $ids) -> update(['status' => 'تم خصمها من الراتب']);
        }
        return redirect() -> route('employee.salaries.employeeSignature', [$employee_id, $previous_salary_month, $current_salary_year, $registerToEmployeeLog -> id]) -> with('success', __('trans.salary added successfully'));
    }

    public function salary_month_details($employee_id, $month, $year)
    {
        $salary_details = EmployeeSalaryLog::with('employee')->where('employee_id', $employee_id) -> whereMonth('salary_month', $month)-> whereYear('salary_month', $year) -> first();
        $employee_salary_signature = Images::where('employee_salary_log_id',$salary_details ->id)->where('type',5) -> latest() -> first();
        return view('employee.salaries.salary_month_details', compact('salary_details', 'month', 'year', 'employee_salary_signature'));
    }

    public function employee_signature($employee_id, $previous_salary_month, $current_salary_year, $employee_salary_log_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $salary_details = EmployeeSalaryLog::with('employee')->where('employee_id', $employee_id) -> whereMonth('salary_month', $previous_salary_month)-> whereYear('salary_month', $current_salary_year) -> first();
        return view('employee.salaries.signature', compact('employee', 'employee_id', 'previous_salary_month', 'current_salary_year', 'employee_salary_log_id', 'salary_details'));
    }

    // Custom Function signature
    public function signature(Request $request)
    {
        if ($request -> ajax())
        {
            $image_data = $this -> uploadSVGImage($request->employee_salary_log_id,$request -> image_data,'employees_signature', 'salary_signature',$request->employee_salary_log_id,'public');
            // Type => 5 [ 5 For employee salary signature]
            Images::create($image_data+ ['type' => 5]+ ['employee_salary_log_id' => $request -> employee_salary_log_id]);
        }
    }

    public function salary_month_details_print(Request $request)
    {
        $employee_salary_log_id = $request -> employee_salary_log_id;
        $print = new Calculate();
        $print ->printSalaryMonthDetails($employee_salary_log_id);

    }
}
