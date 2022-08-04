<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'trn_komentar';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'berita_id', 'komentar_id', 'komentar_tgl', 'komentar_nama', 'komentar_email', 'komentar_konten', 'status_publish'];

    public function Komentar()
    {
        return $this->belongsTo('App\Model\Transaksi\Berita', 'berita_id');
    }
}
