<?php


namespace App\Interfaces\moneySafe;


interface Operation
{
    public function increaseBalance($object_of_model, $amount, $branch_id);

    public function decreaseBalance($object_of_model, $amount, $branch_id);

    public function checkBalance($amount, $branch_id);
}
