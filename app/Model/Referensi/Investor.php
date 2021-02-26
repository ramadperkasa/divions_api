<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_investor';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'investor_name', 'investor_name_en', 'image_id', 'url', 'ishide', 'reorder'];
    protected $appends = ['cover_image', 'type_img'];

    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = Investor::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public function setReorderAttribute($value)
    {
        $reorder = Investor::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
    public function image()
    {
        return $this->belongsTo('App\Model\Transaksi\Image', 'image_id');
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
