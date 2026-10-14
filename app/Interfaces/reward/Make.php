<?php


namespace App\Interfaces\reward;

use App\Models\Reward;
use Carbon\Carbon;

class Make implements Operations
{
    public $amount;
    public $reward_id;
    public $user_id;


    public function getRewardFromPreviousMonth($employee_id)
    {
        $previous_month = Carbon::now()->subMonth()->format('m');
        $current_month = Carbon::now()->month;
        $previous_year = Carbon::now()->subYear()->format('Y');
        $current_year = Carbon::now()->year;

        $reward = Reward::where(['employee_id' => $employee_id, 'type'=> 'يحصل عليها العامل فورا'])
            ->whereMonth('updated_at', $previous_month)
            ->whereYear('updated_at', $current_year)
            ->get();
        if ($current_month == 1)
        {
            $reward = Reward::where(['employee_id' => $employee_id, 'type'=> 'يحصل عليها العامل فورا'])
                ->whereMonth('updated_at', $previous_month)
                ->whereYear('updated_at', $previous_year)
                ->get();
        }
        return $reward ;
    }

    public function getRewardToSalaryFromPreviousMonth($employee_id)
    {
        $previous_month = Carbon::now()->subMonth()->format('m');
        $current_month = Carbon::now()->month;
        $previous_year = Carbon::now()->subYear()->format('Y');
        $current_year = Carbon::now()->year;

        $reward = Reward::where(['employee_id' => $employee_id, 'type'=> 'تضاف الى الراتب', 'status' => 'غير مضافة حتى الان'])
//            ->whereMonth('updated_at', $previous_month)
//            ->whereYear('updated_at', $current_year)
            ->get();
//        if ($current_month == 1)
//        {
//            $reward = Reward::where(['employee_id' => $employee_id, 'type'=> 'تضاف الى الراتب'])
////                ->whereMonth('updated_at', $previous_month)
////                ->whereYear('updated_at', $previous_year)
//                ->get();
//        }
        return $reward ;
    }
}
