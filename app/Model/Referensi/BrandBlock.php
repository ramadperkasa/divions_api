<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class BrandBlock extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_block';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'title', 'brand_id', 'content', 'content_en', 'ishide', 'reorder', 'is_edit', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan'];

    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }

    public function setReorderAttribute($value)
    {
        $reorder =  brandBlock::orderBy('reorder', 'desc')->pluck('reorder')->first();
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
