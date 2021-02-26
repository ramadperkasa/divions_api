<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Folder extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_folder';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'nama_folder', 'ishide', 'isedit', 'reorder'];

    public function gambar()
    {
        return $this->hasMany('App\Model\Transaksi\Image', 'folder_id');
    }

    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = Folder::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public function setReorderAttribute($value)
    {
        $reorder =  Folder::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
    public function setIseditAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['isedit'] = 0;
        } else {
            return $this->attributes['isedit'] = $value;
        }
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->gambar()->delete();
            Storage::disk('galeri_path')->delete(substr($query->id, 8));
        });
    }
}
