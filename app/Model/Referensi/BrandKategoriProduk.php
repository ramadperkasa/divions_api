<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandKategoriProduk extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_kategori_produk';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'brand_id', 'image_id', 'nama', 'name_en', 'ishide', 'reorder', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan'];
    protected $appends = ['cover_image', 'type_img'];

    public function produk()
    {
        return $this->hasMany('App\Model\Referensi\BrandProduk', 'produk_kategori_id', '_id');
    }

    public function image()
    {
        return $this->belongsTo('App\Model\Referensi\BrandImage', 'image_id', '_id');
    }

    public function setReorderAttribute($value)
    {
        $reorder =  BrandKategoriProduk::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
            $query->produk()->delete();
        });
    }

    public function getCoverImageAttribute()
    {
        return $this->image ? $this->image->image : '';
    }

    public function getTypeImgAttribute()
    {
        return $this->image ? $this->image->type : '';
    }
}
