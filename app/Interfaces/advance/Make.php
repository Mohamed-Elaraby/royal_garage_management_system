<?php


namespace App\Interfaces\advance;

use App\Models\Advance;
use App\Models\ScheduledAdvance;
use Carbon\Carbon;

class Make implements Operations
{
    public $amount;
    public $advance_id;
    public $user_id;

    public function createScheduled($amount, $advance_id, $user_id)
    {
        $this -> amount = $amount;
        $this -> advance_id = $advance_id;
        $this -> user_id = $user_id;

        ScheduledAdvance::create(
            [
                'amount' => $this->amount,
                'advance_id' => $this->advance_id,
                'user_id' => $this->user_id,
            ]
        );
    }

    public function getAdvanceFromPreviousMonth($employee_id)
    {
        $previous_month = Carbon::now()->subMonth()->format('m');
        $current_month = Carbon::now()->month;
        $previous_year = Carbon::now()->subYear()->format('Y');
        $current_year = Carbon::now()->year;

        $advance = Advance::where(['employee_id' => $employee_id, 'type'=> 'تخصم مباشرة'])
            ->whereMonth('updated_at', $previous_month)
            ->whereYear('updated_at', $current_year)
            ->get();
        if ($current_month == 1)
        {
            $advance = Advance::where(['employee_id' => $employee_id, 'type'=> 'تخصم مباشرة'])
                ->whereMonth('updated_at', $previous_month)
                ->whereYear('updated_at', $previous_year)
                ->get();
        }
        return $advance ;
    }

    public function getScheduleAdvanceFromPreviousMonth($employee_id)
    {
        $previous_month = Carbon::now()->subMonth()->format('m');
        $current_month = Carbon::now()->month;
        $previous_year = Carbon::now()->subYear()->format('Y');
        $current_year = Carbon::now()->year;

        return $scheduled_advances = ScheduledAdvance::where('status', 'غير مسددة') -> whereHas('advance', function ($query) use ($previous_month, $current_year, $employee_id){
            $query
                ->where(['type' => 'مجدولة', 'employee_id' => $employee_id]);
//                ->whereMonth('updated_at', $previous_month)
//                ->whereYear('updated_at', $current_year);
        })->get();

    }

}
