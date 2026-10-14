<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\MoneySafe;
use App\Models\MoneySafeOpeneingBalance;
use Illuminate\Console\Command;


class GetMoneySafeOpeningBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moneySafe:getOpeningBalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Money Safe Every Day At 5:am And Register The Balance In Special Table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        MoneySafeOpeneingBalance::create([ 'balance' => 500, 'money_safe_id' => 5 ]);
        $branch_list = Branch::all();
        foreach ($branch_list as $branch)
        {
            $moneySafe = MoneySafe::where('branch_id', $branch -> id)->select('id', 'final_amount', 'branch_id')->orderBy('id', 'desc')->first();
//            dd($moneySafe);
            if ($moneySafe)
                MoneySafeOpeneingBalance::create([ 'balance' => $moneySafe -> final_amount, 'money_safe_id' => $moneySafe -> id, 'branch_id' => $moneySafe -> branch_id ]);
        }
    }
}
