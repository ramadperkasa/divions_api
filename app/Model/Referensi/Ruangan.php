<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_ruangan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'varchar';
    public $timestamps = false;
    protected $fillable = ['id', 'nama_ruangan', 'nama_ruangan_en', 'status', 'title_en', 'ishide', 'reorder'];

    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Ruangan::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Ruangan::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
    public function agendaDetail()
    {
        return $this->hasOne(AgendaDetail::class, 'ruangan_id');
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->agendaDetail()->delete();
        });
    }
}
