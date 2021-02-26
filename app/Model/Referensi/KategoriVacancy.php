<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class KategoriVacancy extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_kategori_vacancy';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'nama', 'keterangan', 'nama_en', 'keterangan_en', 'ishide', 'reorder'];

    public function subKategoriVacancy()
    {
        return $this->hasMany('App\Model\Referensi\KategoriSubVacancy', 'parent_id');
    }
    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 0;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
    public function setReorderAttribute($value)
    {
        $reorder = KategoriVacancy::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null || $value == '') {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->subKategoriVacancy()->delete();
        });
    }
}
