<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_upload';

    protected $primaryKey = 'id';

    public $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['judul', 'jenis', 'keterangan', 'file_url', 'file_size', 'ishide', 'reorder', 'folder'];

    public function rundown_file()
    {
        return $this->hasMany('App\Model\Transaksi\PelatihanRundownFile', 'file_url');
    }
    public function bank()
    {
        return $this->hasOne('App\Model\Referensi\Bank', 'logo');
    }
    public function kelengkapanAnggota()
    {
        return $this->hasOne('App\Model\Referensi\AnggotaKelengkapan', 'file_path');
    }

    public function slider()
    {
        return $this->hasMany('App\Model\Referensi\Slider', 'image_url');
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Upload::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null || $value == "") {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
}
