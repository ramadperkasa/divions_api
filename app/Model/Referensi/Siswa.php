<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_siswa';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['id', 'nis', 'nama', 'kelas', 'tahun_ajaran'];
}
