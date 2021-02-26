<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_kontak';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'varchar';
    public $timestamps = false;
    protected $fillable = ['id', 'isi', 'icon', 'nama', 'jenis', 'ket', 'url', 'ishide', 'reorder', 'nama_jenis', 'lat', 'long'];

    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = Contact::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public function setReorderAttribute($value)
    {
        $reorder =  Contact::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
