<?php

namespace App\Model\Referensi;

use App\Model\Transaksi\Image;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_pegawai';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'nama', 'no_hp', 'email', 'facebook', 'twitter', 'instagram', 'ishied', 'reorder', 'image_id'];
    protected $appends = ['images', 'type_img'];

    public function pegawaiDetail()
    {
        return $this->hasMany(PegawaiDetail::class, 'pegawai_id');
    }
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
    public function getImagesAttribute()
    {
        return $this->image ? $this->image->image : '';
    }
    public function getTypeImgAttribute()
    {
        return $this->image ? $this->image->type : '';
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Pegawai::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Pegawai::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
