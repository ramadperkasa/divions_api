<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BlokTemplateDetail extends Model
{
    use \Awobaz\Compoships\Compoships;

    protected $connection = 'mysql2';
    protected $table = 'ref_block_template_detail';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'block_template_id', 'block_id', 'posisi', 'reorder', 'ishide', 'isContainer', 'col'];

    public function blokTemplate()
    {
        return $this->belongsTo('App\Model\Referensi\BlokTemplate', 'block_template_id');
    }
    public function blokTemplateDetailContent()
    {
        return $this->hasMany(BlokTemplateDetailContent::class, ['block_template_detail_id', 'block_template_id'], ['id', 'block_template_id']);
    }
}
