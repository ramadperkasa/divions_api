<?php

namespace App\Model\Transaksi;

use App\Model\Referensi\Bank;
use App\Model\Referensi\Pegawai;
use Illuminate\Database\Eloquent\Model;
use App\Model\Referensi\Quotes;

class Image extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'trn_image';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'image', 'description', 'description_en', 'folder_id', 'type', 'jenis'];

    public function quote()
    {
        return $this->hasMany(Quotes::class, 'foreign_key', 'local_key');
    }
    public function bank()
    {
        return $this->hasMany(Bank::class, 'image_id');
    }
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'image_id');
    }
    public function detailimages()
    {
        return $this->hasMany('App\Model\Transaksi\DetailGallery', 'image_id');
    }
    public function brand()
    {
        return $this->hasMany('App\Model\Referensi\Brand', 'logo_id');
    }
    public function gallery()
    {
        return $this->hasOne('App\Model\Transaksi\Gallery', 'image_id');
    }
    public function folder()
    {
        return $this->belongsTo('App\Model\Referensi\Folder', 'folder_id');
    }
    public function berita()
    {
        return $this->hasMany('App\Model\Transaksi\Berita', 'image_id');
    }
    public function slider()
    {
        return $this->hasMany('App\Model\Referensi\Slider', 'image_id');
    }
    public function investor()
    {
        return $this->hasMany('App\Model\Referensi\Investor', 'image_id');
    }
    public function quotes()
    {
        return $this->hasMany('App\Model\Referensi\Quotes', 'image_id');
    }
    public function vacancy()
    {
        return $this->hasMany('App\Model\Transaksi\Vacancy', 'image_id');
    }


    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = Image::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->detailimages()->delete();
            $query->gallery()->delete();
            $query->berita()->delete();
            $query->slider()->delete();
            $query->investor()->delete();
            $query->quotes()->delete();
            $query->vacancy()->delete();
            $query->brand()->delete();
            $query->bank()->delete();
            $query->pegawai()->delete();
            $query->quote()->delete();
        });
    }
}
