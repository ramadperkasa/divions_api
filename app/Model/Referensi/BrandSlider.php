<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class BrandSlider extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_slider';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'brand_id', 'title', 'title_sub', 'description', 'title_en', 'title_sub_en', 'description_en', 'image_id', 'reorder', 'url', 'target', 'style', 'ishide', 'created_at', 'updated_at', 'deleted_at', 'tipe_link', 'status', 'berita_id', 'block_template_id', 'keterangan'];
    protected $appends = ['image_url', 'type_img'];
    // public function setIdAttribute($value)
    // {
    //     if ($value) {
    //         $this->attributes['id'] = $value;
    //     } else {
    //         $value = BrandSlider::orderBy('id', 'DESC')->pluck('id')->first();
    //         $this->attributes['id'] = $value + 1;
    //     }
    // }

    public function setReorderAttribute($value)
    {
        $reorder =  BrandSlider::orderBy('reorder', 'desc')->pluck('reorder')->first();
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

    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }

    public function brandImage()
    {
        return $this->belongsTo('App\Model\Referensi\BrandImage', 'image_id', '_id');
    }

    public function getImageUrlAttribute()
    {

        return $this->brandImage ? $this->brandImage->image : '';
    }

    public function getTypeImgAttribute()
    {

        return $this->brandImage ? $this->brandImage->type : '';
    }
}
