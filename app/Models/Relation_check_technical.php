<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Relation_check_technical
 *
 * @property int $id
 * @property int $check_id
 * @property int $technical_id
 * @method static \Illuminate\Database\Eloquent\Builder|Relation_check_technical newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Relation_check_technical newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Relation_check_technical query()
 * @mixin \Eloquent
 */
class Relation_check_technical extends Model
{
    protected $fillable = ['check_id', 'technical_id'];
    public $timestamps = false;
}
