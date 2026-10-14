<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneySafeOpeneingBalance extends Model
{
    public $guarded = [];

    public function moneySafes ()
    {
        return $this->hasMany(MoneySafe::class);
    }
}
