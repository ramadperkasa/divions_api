<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class SettingHeader extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_setting_header';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'foto'];
}
