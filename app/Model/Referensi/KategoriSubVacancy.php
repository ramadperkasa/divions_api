<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class KategoriSubVacancy extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_kategori_sub_vacancy';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'parent_id', 'nama', 'keterangan', 'nama_en', 'keterangan_en', 'ishide', 'reorder'];

    public function kategoriVacancy()
    {
        return $this->belongsTo('App\Model\Referensi\KategoriVacancy', 'parent_id');
    }
    public function vacancy()
    {
        return $this->hasMany('App\Model\Transaksi\Vacancy', 'kategori_sub_vacancy_id');
    }
    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = KategoriSubVacancy::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public function setReorderAttribute($value)
    {
        $reorder = KategoriSubVacancy::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
            $query->vacancy()->delete();
        });
    }
}
