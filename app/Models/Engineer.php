<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Engineer
 *
 * @property int $id
 * @property string $name
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Check[] $checks
 * @property-read int|null $checks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Engineer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Engineer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Engineer query()
 * @mixin \Eloquent
 */
class Engineer extends Model
{
    protected $fillable = ['name', 'branch_id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function checks(): HasMany
    {
        return $this -> hasMany(Check::class);
    }
}
