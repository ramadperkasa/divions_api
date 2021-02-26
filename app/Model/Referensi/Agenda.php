<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_slider';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'warna', 'nama_kegiatan', 'nama_kegiatan_en', 'keterangan', 'ishide', 'reorder'];

    public function agendaDetail()
    {
        return $this->hasMany(AgendaDetail::class, 'agenda_id');
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Agenda::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Agenda::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
            $query->agendaDetail()->delete();
        });
    }
}
