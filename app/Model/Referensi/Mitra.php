<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_mitra';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'type_img', 'image_id', 'url', 'nama', 'ishide', 'reorder'];
    protected $appends = ['image', 'type_img'];

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

    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = Mitra::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public function setReorderAttribute($value)
    {
        $reorder =  Mitra::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
}
