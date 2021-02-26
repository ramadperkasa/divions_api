<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class PegawaiDetail extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_agenda_detail';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['pegawai_id', 'jabatan_id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    public function setReorderAttribute($value)
    {
        $reorder =  PegawaiDetail::orderBy('reorder', 'desc')->pluck('reorder')->first();
        if ($value == null || $value == '') {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 0;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
}
