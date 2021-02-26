<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'brand_kategori_id', 'nama_brand', 'nama_brand_en', 'description', 'logo_id', 'ishide', 'url', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan', 'slug'];
    protected $appends = ['cover_image', 'type_img'];

    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 0;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
    public function image()
    {
        return $this->belongsTo('App\Model\Transaksi\Image', 'logo_id');
    }
    public function brandKategori()
    {
        return $this->belongsTo('App\Model\Referensi\BrandKategori', 'brand_kategori_id', '_id');
    }
    public function vacancy()
    {
        return $this->hasMany('App\Model\Transaksi\Vacancy', 'brand_id', '_id');
    }
    public function brandBlock()
    {
        return $this->hasMany('App\Model\Referensi\BrandBlock', 'brand_id');
    }
    public function brandFolder()
    {
        return $this->hasMany('App\Model\Referensi\BrandFolder', 'brand_id');
    }
    public function brandimage()
    {
        return $this->hasMany('App\Model\Referensi\BrandImage', 'brand_id');
    }
    public function brandKategoriProduk()
    {
        return $this->hasMany('App\Model\Referensi\BrandKategoriProduk', 'brand_id');
    }
    public function brandKontak()
    {
        return $this->hasMany('App\Model\Referensi\BrandKontak', 'brand_id');
    }
    public function brandProduk()
    {
        return $this->hasMany('App\Model\Referensi\BrandProduk', 'brand_id');
    }
    public function brandSlider()
    {
        return $this->hasMany('App\Model\Referensi\BrandSlider', 'brand_id');
    }

    public function getCoverImageAttribute()
    {
        return $this->image ? $this->image->image : '';
    }

    public function getTypeImgAttribute()
    {
        return $this->image ? $this->image->type : '';
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            // $query->brandFolder()->delete();
            // $query->brandImage()->delete();
            // $query->brandKategoriProduk()->delete();
            // $query->brandKontak()->delete();
            // $query->brandProduk()->delete();
            // $query->brandSlider()->delete();
            $query->vacancy()->delete();
        });
    }
}
