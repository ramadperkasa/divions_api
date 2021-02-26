<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_broadcast';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'created_at', 'total_news', 'total_subscribe', 'updated_at'];
}
