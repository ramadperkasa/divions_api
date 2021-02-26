<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandBlockTemplateDetail extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_block_template_detail';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', '_id', 'brand_id', 'block_template_id', 'block_id', 'posisi', 'col', 'reorder', 'ishide'];

    public function blok()
    {
        return $this->belongsTo('App\Model\Referensi\BrandBlock', 'block_id');
    }

    public function blokTemplate()
    {
        return $this->belongsTo('App\Model\Referensi\BrandBlockTemplate', 'block_template_id');
    }
}
