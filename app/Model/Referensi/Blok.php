<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Blok extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_block';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'title', 'content', 'content_en', 'ishide', 'reorder', 'is_edit'];

    public function setReorderAttribute($value)
    {
        $reorder =  Blok::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null) {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = Blok::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 1;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }

    public function blokTemplateDetail()
    {
        return $this->hasMany('App\Model\Referensi\BlokTemplateDetail', 'block_id');
    }
}
