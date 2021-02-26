<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Rated extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'trn_rated';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'berita_id', 'user_id', 'rated_nilai', 'isHide'];
    protected $appends = ['image'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function Berita()
    {
        return $this->belongsTo(Berita::class, 'berita_id');
    }
    public function getImageAttribute()
    {
        return $this->user ? $this->user->avatar : '';
    }
}
