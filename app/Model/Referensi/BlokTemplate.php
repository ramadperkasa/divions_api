<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BlokTemplate extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_block_template';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'nama'];

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
        return $this->hasMany('App\Model\Referensi\BlokTemplateDetail', 'block_template_id');
    }
    public function blokTemplateDetailContent()
    {
        return $this->hasMany(BlokTemplateDetailContent::class, 'block_template_id');
    }
    public function menu()
    {
        return $this->hasMany('App\Model\Referensi\Menu', 'block_template_id');
    }
    public function menuSub()
    {
        return $this->hasMany('App\Model\Referensi\MenuSub', 'block_template_id');
    }
}
