<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class GalleryKategori extends Model
{
  protected $connection = 'mysql2';
  protected $table = 'ref_gallery_kategori';
  protected $primaryKey = 'id';
  public $incrementing = true;
  public $keyType = 'int';
  public $timestamps = false;
  protected $fillable = ['id', 'nama', 'nama_en', 'ishide', 'reorder'];

  public function gallery()
  {
    return $this->hasOne('App\Model\Transaksi\Gallery', 'id_kategori');
  }

  public function setIdAttribute($value)
  {
    if ($value == null) {
      $id = GalleryKategori::orderBy('id', 'DESC')->pluck('id')->first();

      $this->attributes['id'] = $id + 1;
    } else {

      $this->attributes['id'] = $value;
    }
  }
  public function setReorderAttribute($value)
  {
    $reorder =  GalleryKategori::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
      $query->gallery()->delete();
    });
  }
}
