<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;
use App\Model\Referensi\BrandProdukDetail;
use App\Model\Transaksi\WarnaDetail;

class BrandProduk extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_produk';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'image_id', 'produk_kategori_id', 'brand_id', 'nama', 'nama_en', 'deskripsi', 'deskripsi_en', 'harga', 'ishide', 'reorder', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan', 'slug', 'subtitle'];
    protected $appends = ['cover_image', 'type_img', 'judul', 'subjudul'];


   
    public function brandKategoriProduk()
    {
        return $this->belongsTo('App\Model\Referensi\BrandKategoriProduk', 'produk_kategori_id', '_id');
    }
    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }
    public function image()
    {
        return $this->belongsTo('App\Model\Referensi\BrandImage', 'image_id', '_id');
    }
    public function brandProdukDetail()
    {
        return $this->hasMany(BrandProdukDetail::class, 'brand_product_id', '_id');
    }
    public function warnaDetail()
    {
        return $this->hasMany(WarnaDetail::class, 'produk_id');
    }
    public function setReorderAttribute($value)
    {
        $reorder =  BrandProduk::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
    public function getCoverImageAttribute()
    {
        return $this->image ? $this->image->image : '';
    }

    public function getTypeImgAttribute()
    {
        return $this->image ? $this->image->type : '';
    }
    public function getJudulAttribute()
    {
        return $this->nama ? strip_tags($this->nama) : '';
    }

    public function getSubJudulAttribute()
    {
        return $this->subtitle ? strip_tags($this->subtitle) : '';
    }
}
