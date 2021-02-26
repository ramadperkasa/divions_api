<?php

namespace App\Model\Transaksi;

use App\Model\Referensi\RekeningBank;
use App\Model\Referensi\TypeInfaq;
use Illuminate\Database\Eloquent\Model;

class Infaq extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_infaq';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'tgl', 'nama', 'ket', 'debit', 'credit', 'type_id', 'rekening_id', 'ishide'];

    public function typeInfaq()
    {
        return $this->belongsTo(TypeInfaq::class, 'type_id');
    }
    public function rekening()
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_id');
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Infaq::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Infaq::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
