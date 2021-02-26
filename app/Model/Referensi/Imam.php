<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Imam extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_imam';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'nama'];

    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Imam::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Imam::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
        return $this->hasMany(AgendaDetail::class, 'imam_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->agendaDetail()->delete();
        });
    }
}
