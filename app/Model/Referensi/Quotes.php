<?php

namespace App\Model\Referensi;

use App\Model\Transaksi\Image;
use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_quotes';

    protected $primaryKey = 'id';

    public $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['id', 'deskripsi', 'image_id', 'oleh', 'jabatan', 'reorder', 'ishide'];

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

    public function setReorderAttribute($value)
    {
        $reorder =  Quotes::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null || $value == '') {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 1;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
}
