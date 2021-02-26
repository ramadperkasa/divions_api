<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'trn_vacancy';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'brand_id', 'tgl_expired', 'kategori_sub_vacancy_id', 'tgl_publikasi', 'image_id', 'judul', 'sinopsis', 'isi_berita', 'judul_en', 'sinopsis_en', 'isi_berita_en', 'komentar', 'komentar_auto', 'rated', 'meta_tag', 'total_view', 'total_share', 'last_view', 'ishide', 'reorder', 'slug', 'posted_by'];
    protected $appends = ['cover_image', 'type_img'];

    public function admin()
    {
        return $this->belongsTo('App\Admin', 'posted_by');
    }
    public function image()
    {
        return $this->belongsTo('App\Model\Transaksi\Image', 'image_id');
    }
    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }
    public function kategoriSubVacancy()
    {
        return $this->belongsTo('App\Model\Referensi\KategoriSubVacancy', 'kategori_sub_vacancy_id');
    }
    public function setReorderAttribute($value)
    {
        $reorder = Berita::orderBy('reorder', 'desc')->pluck('reorder')->first();

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

    public function getTypeImgAttribute()
    {
        return $this->image ? $this->image->type : '';
    }
}
