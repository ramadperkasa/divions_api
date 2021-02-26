<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'trn_berita';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['id', 'id_kategori', 'tgl_publikasi', 'image_id', 'judul', 'sinopsis', 'isi_berita', 'judul_en', 'sinopsis_en', 'isi_berita_en', 'komentar', 'komentar_auto', 'rated', 'meta_tag', 'total_view', 'last_view', 'ishide', 'reorder', 'slug', 'posted_by', 'published_at', 'status'];

    protected $appends = ['cover_image', 'type_img', 'rate'];

    public function Kategori()
    {
        return $this->belongsTo('App\Model\Referensi\Kategori', 'id_kategori');
    }
    public function admin()
    {
        return $this->belongsTo('App\Admin', 'posted_by');
    }
    public function slider()
    {
        return $this->hasMany('App\Model\Referensi\Slider', 'berita_id');
    }
    public function komentar()
    {
        return $this->hasMany('App\Model\Transaksi\Komentar', 'berita_id');
    }
    public function rated()
    {
        return $this->hasMany(Rated::class, 'berita_id');
    }
    public function image()
    {
        return $this->belongsTo('App\Model\Transaksi\Image', 'image_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->slider()->delete();
            $query->komentar()->delete();
            $query->rated()->delete();
        });
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Berita::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null) {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
    public function setStatusAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['status'] = 0;
        } else {
            return $this->attributes['status'] = $value;
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
    public function setTotalViewAttribute($value)
    {
        if ($value == null) {
            return $this->attributes['total_view'] = 0;
        } else {
            return $this->attributes['total_view'] = $value;
        }
    }

    public function setCoverImageAttribute($value)
    {
        if ($value == null) {
            return $this->attributes['image_id'] = '/gambar/default.png';
        } else {
            return $this->attributes['image_id'] = $value;
        }
    }


    public function getCoverImageAttribute()
    {
        return $this->image ? $this->image->image : '';
    }

    public function getRateAttribute()
    {
        return 5;
    }

    public function getTypeImgAttribute()
    {
        return $this->image ? $this->image->type : '';
    }
}
