<?php


namespace App\Interfaces\advance;


interface Operations
{
    public function createScheduled($amount, $advance_id, $user_id);

    public function getAdvanceFromPreviousMonth($employee_id);

    public function getScheduleAdvanceFromPreviousMonth($employee_id);


}
