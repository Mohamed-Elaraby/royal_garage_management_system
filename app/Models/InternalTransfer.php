<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalTransfer extends Model
{
    protected $fillable = [
        'code', 'name', 'price', 'quantity', 'discount', 'discount_type', 'discount_amount', 'price_after_discount', 'from_branch', 'to_branch', 'user_id', 'administrator_id', 'request_action_date', 'sub_category_id', 'status'
    ];

    public function f_branch()
    {
        return $this->belongsTo(Branch::class, 'from_branch');
    }
    public function t_branch()
    {
        return $this->belongsTo(Branch::class, 'to_branch');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategories::class);
    }
}
