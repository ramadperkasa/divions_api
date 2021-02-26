<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BlokTemplateDetailContent extends Model
{
    use \Awobaz\Compoships\Compoships;

    protected $connection = 'mysql2';
    protected $table = 'ref_block_template_detail_content';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'block_template_id', 'block_template_detail_id', 'block_id', 'col', 'reorder', 'ishide'];

    public function blok()
    {
        return $this->belongsTo('App\Model\Referensi\Blok', 'block_id');
    }
    public function blokTemplate()
    {
        return $this->belongsTo('App\Model\Referensi\BlokTemplate', 'block_template_id');
    }
    public function blokTemplateDetail()
    {
        return $this->belongsTo(BlokTemplateDetail::class, ['block_template_detail_id', 'block_template_id'], ['id', 'block_template_id']);
    }
}
