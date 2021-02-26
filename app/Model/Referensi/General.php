<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_general';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', '_id', 'judul', 'isi', 'deskripsi', 'item', 'isedit', 'reorder', 'type_input', 'status_type', 'created_at', 'updated_at', 'deleted_at', 'status', 'keterangan'];
}
