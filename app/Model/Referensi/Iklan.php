<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Iklan extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_iklan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'nama', 'url', 'foto_iklan', 'priority', 'type', 'ishide', 'reorder'];

    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 0;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
}
