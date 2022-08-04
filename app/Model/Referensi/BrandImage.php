<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_image';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'brand_id', 'description', 'description_en', 'type', 'image', 'folder_id', 'jenis', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan'];


    public function folder()
    {
        return $this->belongsTo('App\Model\Referensi\BrandFolder', 'folder_id', '_id');
    }
    public function brand()
    {
        return $this->hasMany('App\Model\Referensi\Brand', 'logo_id', '_id');
    }
    public function brands()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }
    public function brandProduk()
    {
        return $this->belongsTo('App\Model\Referensi\BrandProduk', 'image_id', '_id');
    }
    public function brandProdukKategori()
    {
        return $this->belongsTo('App\Model\Referensi\BrandKategoriProduk', 'image_id', '_id');
    }
    public function brandSlider()
    {
        return $this->hasMany('App\Model\Referensi\BrandSlider', 'image_id', '_id');
    }
    // public function setIdAttribute($value)
    // {
    //     if ($value == null) {
    //         $id = BrandImage::orderBy('id', 'DESC')->pluck('id')->first();

    //         $this->attributes['id'] = $id + 1;
    //     } else {

    //         $this->attributes['id'] = $value;
    //     }
    // }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->brandSlider()->delete();
            $query->brand()->delete();
        });
    }
}
