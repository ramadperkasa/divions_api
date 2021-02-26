<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandKategori extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_kategori';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'nama', 'nama_en', 'ishide', 'reorder', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan'];

    public function brand()
    {
        return $this->hasMany('App\Model\Referensi\Brand', 'brand_kategori_id', '_id');
    }

    // public function setIdAttribute($value)
    // {
    //     if ($value == null) {
    //         $id = BrandKategori::orderBy('id', 'DESC')->pluck('id')->first();

    //         $this->attributes['id'] = $id + 1;
    //     } else {

    //         $this->attributes['id'] = $value;
    //     }
    // }

    public function setReorderAttribute($value)
    {
        $reorder =  BrandKategori::orderBy('reorder', 'desc')->pluck('reorder')->first();

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

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->brand()->delete();
        });
    }
}
