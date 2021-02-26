<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class SettingBooking extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_setting_booking';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'jam_mulai', 'jam_selesai'];

    public function agendaDetail()
    {
        return $this->hasOne(AgendaDetail::class, 'id_setting_booking');
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->agendaDetail()->delete();
        });
    }
}
