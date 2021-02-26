<?php

namespace App\Model\Referensi;

use App\Model\Transaksi\Infaq;
use Illuminate\Database\Eloquent\Model;

class TypeInfaq extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_type_infaq';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'nama', 'ket', 'ishide', 'reorder'];

    public function infaq()
    {
        return $this->hasMany(Infaq::class, 'type_id');
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = TypeInfaq::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  TypeInfaq::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
            $query->infaq()->delete();
        });
    }
}
