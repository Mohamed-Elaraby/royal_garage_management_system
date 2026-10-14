<?php


namespace App\Interfaces\salary;


use App\Models\Advance;
use App\Models\Car;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\EmployeeSalaryLog;
use App\Models\Images;
use App\Models\Reward;
use App\Models\Salary;
use Carbon\Carbon;
use PDF;

class Calculate implements Operations
{

    public function totalSalary($employee_id)
    {
        $salary = Salary::where('employee_id', $employee_id)
            ->first(['main', 'housing_allowance', 'transfer_allowance', 'travel_allowance', 'end_service_allowance', 'other_allowance']);
        return array_sum($salary->toArray()) ;
    }

    public function salaryPerDay($totalSalary)
    {
        $number_days_of_month = Carbon::now()->daysInMonth;
        $employee_salary_per_day = $totalSalary / $number_days_of_month ;
        return number_format($employee_salary_per_day, 2);
    }

    public function printSalaryMonthDetails($employee_salary_log)
    {
        $employee_salary_log_id = $employee_salary_log;
//        $data = [];
        $data['employee_salary_log'] = EmployeeSalaryLog::findOrFail($employee_salary_log_id);
        $data['employee_salary_signature'] = Images::where('employee_salary_log_id',$employee_salary_log_id)->where('type',5) -> latest() -> first();
        $mpdf = PDF::loadView('employee.salaries.salary_month_details_print', $data, [], [
            'margin_top' => 100,
            'margin_header' => 10,
            'margin_footer' => 20,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;

        $employee_name = $data['employee_salary_log']->employee -> name ;
        $month = Carbon::parse($data['employee_salary_log']->salary_month)->month;
        $year = Carbon::parse($data['employee_salary_log']->salary_month)->year;
//         $mpdf->download($data['sale_order']->invoice_number.'.pdf');
//        if ($request->download)
//        {
//            return $mpdf->download($employee_name . ' - ' .$month. ' - ' .$year.'.pdf');
//
//        }

        return $mpdf->stream($employee_name . ' - ' .$month. ' - ' .$year.'.pdf');
    }


    public function check_advance_greater_than_limit($advance_amount, $employee_id)
    {
        $current_month = Carbon::now()->month;
        $current_year = Carbon::now()->year;

        $advances_during_the_month = Advance::where('employee_id', $employee_id)
            ->where('type', 'تخصم مباشرة')
            ->whereMonth('updated_at', $current_month)
            ->whereYear('updated_at', $current_year)
            ->sum('amount');

        $rewards_during_the_month = Reward::where('employee_id', $employee_id)
            ->where('type', 'يحصل عليها العامل فورا')
            ->whereMonth('updated_at', $current_month)
            ->whereYear('updated_at', $current_year)
            ->sum('amount');

        $salary = new Calculate();
        $employee_salary = $salary ->totalSalary($employee_id);

        if ($advance_amount > ($employee_salary + $rewards_during_the_month - $advances_during_the_month))
        {
            $details = [
                'advance_amount' => $advance_amount,
                'employee_salary' => $employee_salary,
                'rewards_during_the_month' => $rewards_during_the_month,
                'advances_during_the_month' => $advances_during_the_month,
                'total_employee_receives' => ($employee_salary + $rewards_during_the_month - $advances_during_the_month)
            ];
            return $details ;
        }
    }
}
