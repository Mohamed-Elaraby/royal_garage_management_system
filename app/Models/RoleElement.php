<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RoleElement
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|RoleElement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleElement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleElement query()
 * @mixin \Eloquent
 */
class RoleElement extends Model
{
    protected $guarded = [];
    public $timestamps = false;
}
