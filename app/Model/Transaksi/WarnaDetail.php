<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class WarnaDetail extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_warna_detail';

    protected $primaryKey = 'id';

    public $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['id', 'warna_id', 'brand_produk_id', 'brand_id', 'image_id', 'reorder', 'ishide'];

    public function warna()
    {
        return $this->belongsTo('App\Model\Referensi\Warna', 'warna_id');
    }
    public function brandProduk()
    {
        return $this->belongsTo('App\Model\Referensi\BrandProduk', 'brand_produk_id', '_id');
    }
    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }
    public function image()
    {
        return $this->belongsTo('App\Model\Referensi\BrandImage', 'image_id', '_id');
    }
    
    public function setReorderAttribute($value)
    {
        $reorder =  WarnaDetail::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null || $value == '') {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
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
}
