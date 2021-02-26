<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_inventaris';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'nama', 'jumlah', 'satuan', 'kondisi', 'jenis_inventaris_id', 'keterangan'];

    public function jenisInventaris()
    {
        return $this->belongsTo(JenisInventaris::class, 'jenis_inventaris_id');
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Inventaris::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Inventaris::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
