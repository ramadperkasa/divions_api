<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandKontak extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_kontak';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'brand_id', 'nama', 'isi', 'url', 'jenis', 'icon', 'reorder', 'ishide', 'nama_jenis', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan', 'lat', 'long'];


    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }


    public function setReorderAttribute($value)
    {
        $reorder =  BrandKontak::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
