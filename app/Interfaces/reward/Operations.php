<?php


namespace App\Interfaces\reward;


interface Operations
{
    public function getRewardFromPreviousMonth($employee_id);
    public function getRewardToSalaryFromPreviousMonth($employee_id);
}
