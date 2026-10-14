<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Images
 *
 * @property int $id
 * @property string $image_name
 * @property string $image_path
 * @property int $type 1=>chocks, 2=>device_report, 3=>client_signature_entry, 4=>client_signature_exit
 * @property int $check_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $check_images_path
 * @method static \Illuminate\Database\Eloquent\Builder|Images newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Images newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Images query()
 * @mixin \Eloquent
 */
class Images extends Model
{
//    protected $fillable = ['image_name', 'type', 'image_path', 'check_id', 'employee_salary_log_id'];

    protected $guarded = [];

    protected $appends = ['check_images_path'];

    public function getCheckImagesPathAttribute()
    {
        return 'storage' . DIRECTORY_SEPARATOR . $this->image_path . DIRECTORY_SEPARATOR . $this->image_name;
    }

    public function check()
    {
        return $this -> belongsTo(Check::class);
    }
}
