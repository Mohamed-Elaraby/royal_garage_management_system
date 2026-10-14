<?php

namespace App\Interfaces\product;

interface QuantityOperations
{
    public function increaseQuantity($branch_id, $product_code, $newQuantity);
}
