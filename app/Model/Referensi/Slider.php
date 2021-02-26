<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_slider';

    protected $primaryKey = 'id';

    public $keyType = 'int';

    protected $fillable = ['id', 'title', 'title_sub', 'description', 'title_en', 'title_sub_en', 'description_en', 'image_id', 'url', 'target', 'style', 'ishide', 'reorder', 'created', 'tipe_link', 'berita_id', 'block_template_id'];

    protected $appends = ['image_url', 'type_img'];

    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Slider::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Slider::orderBy('reorder', 'desc')->pluck('reorder')->first();
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

    public function berita()
    {
        return $this->belongsTo('App\Model\Transaksi\Berita', 'berita_id');
    }
    public function image()
    {
        return $this->belongsTo('App\Model\Transaksi\Image', 'image_id');
    }

    public function getImageUrlAttribute()
    {

        return $this->image ? $this->image->image : '';
    }

    public function getTypeImgAttribute()
    {

        return $this->image ? $this->image->type : '';
    }
}
