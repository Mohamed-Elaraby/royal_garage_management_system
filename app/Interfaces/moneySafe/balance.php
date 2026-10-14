<?php


namespace App\Interfaces\moneySafe;


use App\Models\MoneySafe;

class balance implements Operation
{
    public function increaseBalance($object_of_model, $amount, $branch_id)
    {
        $user_id = auth()->user()->id;
        /* Update Money Safe Amount */
        $last_amount_money_safe = MoneySafe::where('branch_id', $branch_id)->get()->last();
        $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;
        $object_of_model->moneySafes()->create([
            'amount_paid' => $amount,
            'final_amount' => ($final_amount_money_safe + ($amount)),
            'user_id' => $user_id,
            'branch_id' => $branch_id,
        ]);

    }

    public function decreaseBalance($object_of_model, $amount, $branch_id)
    {
        $user_id = auth()->user()->id;
        /* Update Money Safe Amount */
        $last_amount_money_safe = MoneySafe::where('branch_id', $branch_id)->get()->last();
        $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;
        $object_of_model->moneySafes()->create([
            'amount_paid' => $amount,
            'final_amount' => ($final_amount_money_safe - ($amount)),
            'user_id' => $user_id,
            'branch_id' => $branch_id,
        ]);
    }

    public function checkBalance($amount, $branch_id)
    {
        $last_amount = MoneySafe::where('branch_id', $branch_id) -> get()->last(); // get last amount
        $final_amount = $last_amount ? $last_amount->final_amount : 0; // if last amount not empty set final amount else final amount equal zero

        if ($final_amount == 0 || $final_amount < $amount) { // on mount in the safe is not enough redirect

            return true;
//            return redirect()->route('admin.moneySafe.index', $branch_id)->with('delete', __('trans.the amount in the safe is not enough'));

        }
    }
}
