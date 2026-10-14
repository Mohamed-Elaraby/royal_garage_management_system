<?php


namespace App\Interfaces\vacation;


interface Operations
{
    public function totalDays($start_date, $end_date);
    public function discountAmount($total_vacation_days, $salary_per_day);
    public function getPaidVacationFromPreviousMonth($employee_id);
    public function getVacationsDeductedFromTheSalaryFromPreviousMonth($employee_id);
}
