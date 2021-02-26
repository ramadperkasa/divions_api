<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandBlockTemplateDetailContent extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_block_template_detail_content';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', '_id', 'brand_id', 'block_template_id', 'block_template_detail_id', 'block_id', 'col', 'reorder', 'ishide'];

    public function blok()
    {
        return $this->belongsTo('App\Model\Referensi\Block', 'block_id');
    }

    public function blokTemplate()
    {
        return $this->belongsTo('App\Model\Referensi\BlockTemplate', 'block_template_id');
    }
}
