<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;
use App\Model\Referensi\BrandProduk;

class BrandProdukDetail extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_brand_produk_detail';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', '_id', 'brand_id', 'brand_product_id', 'title', 'content', 'ishide', 'reorder', 'updated_at', 'created_at', 'keterangan', 'brand_product_id_key'];

    public function brandProduk()
    {
        return $this->belongsTo(BrandProduk::class, 'brand_product_id', '_id');
    }
    public function setIdAttribute($value)
    {
        if ($value == null) {
            $id = BrandProdukDetail::orderBy('id', 'DESC')->pluck('id')->first();

            $this->attributes['id'] = $id + 1;
        } else {

            $this->attributes['id'] = $value;
        }
    }
}
