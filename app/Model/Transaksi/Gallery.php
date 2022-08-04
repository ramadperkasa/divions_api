<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'trn_gallery';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'id_kategori', 'image_id', 'tgl_publish', 'judul', 'judul_en', 'ishide', 'reorder'];
    protected $appends = ['image', 'type_img', 'kategoriGallery'];

    public function kategori()
    {
        return $this->belongsTo('App\Model\Referensi\GalleryKategori', 'id_kategori');
    }
    public function detailgallery()
    {
        return $this->hasMany('App\Model\Transaksi\DetailGallery', 'gallery_id');
    }
    public function images()
    {
        return $this->belongsTo('App\Model\Transaksi\Image', 'image_id');
    }

    public function getKategoriGalleryAttribute()
    {
        return $this->kategori ? $this->kategori->nama : '';
    }

    public function getImageAttribute()
    {
        return $this->images ? $this->images->image : '';
    }

    public function getTypeImgAttribute()
    {
        return $this->images ? $this->images->type : '';
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Gallery::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null) {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
    public function setIshideAttribute($value)
    {
        if ($value == null) {
            return $this->attributes['ishide'] = 0;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = Gallery::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->detailgallery()->delete();
        });
    }
}
