<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Youtube extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_youtube';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'image', 'judul', 'judul_en', 'sinopsis', 'sinopsis_en', 'url', 'ishide', 'reorder'];

    public function setReorderAttribute($value)
    {
        $reorder =  Youtube::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null) {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
}
