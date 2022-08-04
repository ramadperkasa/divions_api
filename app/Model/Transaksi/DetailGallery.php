<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class DetailGallery extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'trn_detail_gallery';
    protected $primaryKey = 'gallery_id';
    public $incrementing = false;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'type_img', 'gallery_id', 'image_id'];
    protected $appends = ['image', 'type_img'];

    public function gallery()
    {
        return $this->belongsTo('App\Model\Transaksi\Gallery', 'gallery_id');
    }

    public function images()
    {
        return $this->belongsTo('App\Model\Transaksi\Image', 'image_id');
    }

    public function getImageAttribute()
    {
        return $this->images ? $this->images->image : '';
    }

    public function getTypeImgAttribute()
    {
        return $this->images ? $this->images->type : '';
    }
}
