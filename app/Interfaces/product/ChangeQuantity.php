<?php

namespace App\Interfaces\product;

use App\Models\Product;

class ChangeQuantity implements QuantityOperations
{

    public function increaseQuantity($branch_id, $product_code, $newQuantity)
    {
        $product_exist = Product::where(['branch_id'=> $branch_id, 'code' => $product_code])->first();

        if ($product_exist)
        {
            $product_exist -> update(['quantity' => $product_exist -> quantity + $newQuantity]);
        }
    }

    public function decreaseQuantity($branch_id, $product_code, $newQuantity)
    {
        $product_exist = Product::where(['branch_id'=> $branch_id, 'code' => $product_code])->first();

        if ($product_exist)
        {
            $product_exist -> update(['quantity' => $product_exist -> quantity - $newQuantity]);
        }
    }
}
