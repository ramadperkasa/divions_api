<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandBlockTemplate extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_block_template';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', '_id', 'brand_id', 'nama', 'is_active', 'ishide'];

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
        return $this->hasMany('App\Model\Referensi\BrandBlockTemplateDetail', 'block_template_id');
    }
}
