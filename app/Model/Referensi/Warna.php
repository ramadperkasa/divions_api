<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Warna extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_warna';

    protected $primaryKey = 'id';

    public $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['id', 'nama', 'nama_singkat', 'code_hex', 'code_rgb', 'reorder', 'ishide'];

    public function setReorderAttribute($value)
    {
        $reorder =  Warna::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null || $value == '') {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 1;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
}
