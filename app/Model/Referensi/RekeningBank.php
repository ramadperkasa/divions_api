<?php

namespace App\Model\Referensi;

use App\Model\Transaksi\Infaq;
use Illuminate\Database\Eloquent\Model;

class RekeningBank extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_rekening_bank';

    protected $primaryKey = 'id';

    public $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['id', 'bank_id', 'cabang', 'rekening_no', 'rekening_nama', 'ishide', 'reorder', 'singkatan'];

    public function infaq()
    {
        return $this->hasMany(Infaq::class, 'rekening_id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function scopeShow($query)
    {
        return $query->where('ishide', 0);
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
        $reorder =  RekeningBank::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
