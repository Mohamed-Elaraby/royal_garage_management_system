<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\File
 *
 * @property int $id
 * @property string $name
 * @property int $type 2=>device_report_file
 * @property string|null $path
 * @property string $extension
 * @property int $check_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Check $check
 * @property-read mixed $check_files_path
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @mixin \Eloquent
 */
class File extends Model
{
    protected $fillable = ['name', 'type', 'path', 'extension', 'check_id'];

    public function check()
    {
        return $this->belongsTo(Check::class);
    }

    protected $appends = ['check_files_path'];

    public function getCheckFilesPathAttribute()
    {
        return 'storage' . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . $this->name;
    }
}
