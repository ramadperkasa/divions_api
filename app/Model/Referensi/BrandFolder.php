<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandFolder extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_folder';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'brand_id', 'nama_folder', 'ishide', 'reorder', 'isedit', 'created_at', 'updated_at', 'deleted_at', 'status', 'keteragan'];



    public function brandImage()
    {
        return $this->hasMany('App\Model\Referensi\BrandImage', 'folder_id', '_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }

    public function brands()
    {
        return $this->hasMany('App\Model\Referensi\Brand', 'brand_id');
    }

    public function setReorderAttribute($value)
    {
        $reorder =  BrandFolder::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
            $query->brandImage()->delete();
        });
    }
}
