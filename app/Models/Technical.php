<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Technical
 *
 * @property int $id
 * @property string $name
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @method static \Illuminate\Database\Eloquent\Builder|Technical newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Technical newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Technical query()
 * @method static \Illuminate\Database\Eloquent\Builder|Technical spicColumn()
 * @mixin \Eloquent
 */
class Technical extends Model
{
    protected $fillable = ['name', 'branch_id'];

    protected $hidden = ['pivot'];


    public function scopeSpicColumn($query)
    {
        return $query -> select('id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function checks()
    {
        return $this -> belongsToMany(Check::class, 'relation_check_technicals', 'technical_id', 'check_id');
    }
}
