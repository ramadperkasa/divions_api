<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_subscribe';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'email', 'ishide'];
}
