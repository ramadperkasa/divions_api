<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_ticker';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'title', 'title_en', 'url', 'target', 'created_at', 'ishide', 'update_at'];

    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 1;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
}
