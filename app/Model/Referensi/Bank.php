<?php

namespace App\Model\Referensi;

use App\Model\Transaksi\Image;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_bank';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'kode', 'nama', 'singkatan', 'image_id', 'ishide', 'reorder'];
    protected $appends = ['images', 'type_img'];

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
    public function rekeningBank()
    {
        return $this->hasMany(RekeningBank::class, 'bank_id');
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Bank::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Bank::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
            $query->rekeningBank()->delete();
        });
    }
}
