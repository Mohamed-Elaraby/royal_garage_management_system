<?php


namespace App\Interfaces\vacation;


use App\Models\Vacation;
use Carbon\Carbon;

class Calculate implements Operations
{
    public function totalDays($start_date, $end_date)
    {
        $start_vacation = Carbon::parse($start_date);
        $end_vacation   = Carbon::parse($end_date);
        return $end_vacation -> diffInDays ($start_vacation);
    }

    public function discountAmount($total_vacation_days, $salary_per_day)
    {
        $amount = $total_vacation_days * $salary_per_day ;
        return number_format(round($amount), 2, '.', '');
    }

    public function getPaidVacationFromPreviousMonth($employee_id)
    {
        $previous_month = Carbon::now()->subMonth()->format('m');
        $current_month = Carbon::now()->month;
        $previous_year = Carbon::now()->subYear()->format('Y');
        $current_year = Carbon::now()->year;

        $vacation = Vacation::where(['type'=> 'مدفوعة الاجر', 'employee_id' => $employee_id])
            ->whereMonth('updated_at', $previous_month)
            ->whereYear('updated_at', $current_year)
            ->get();
        if ($current_month == 1)
        {
            $vacation = Vacation::where(['type'=> 'مدفوعة الاجر', 'employee_id' => $employee_id])
                ->whereMonth('updated_at', $previous_month)
                ->whereYear('updated_at', $previous_year)
                ->get();
        }
        return $vacation ;
    }

    public function getVacationsDeductedFromTheSalaryFromPreviousMonth($employee_id)
    {
        $previous_month = Carbon::now()->subMonth()->format('m');
        $current_month = Carbon::now()->month;
        $previous_year = Carbon::now()->subYear()->format('Y');
        $current_year = Carbon::now()->year;

        $vacation = Vacation::where(['type'=> 'تخصم من الراتب', 'status' => 'لم تخصم حتى الان', 'employee_id' => $employee_id])->get();

        return $vacation ;
    }
}
