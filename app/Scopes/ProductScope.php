<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductScope  implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'deputy_manager']))
        {
            $builder->where('branch_id', '=', Auth::user()->branch_id);
        }
    }
}
