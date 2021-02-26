<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_jenis_kegiatan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'nama', 'keterangan', 'nama_en', 'keterangan_en', 'ishide', 'reorder'];

    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = JenisKegiatan::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  JenisKegiatan::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
    public function agendaDetail()
    {
        return $this->hasMany(AgendaDetail::class, 'jenis_kegiatan_id');
    }
}
