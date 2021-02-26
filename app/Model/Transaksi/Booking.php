<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'trn_booking';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'varchar';
    public $timestamps = false;
    protected $fillable = ['id', 'agenda_id', 'jenis_kegiatan_id', 'warna', 'tgl', 'jam_mulai', 'jam_selesai', 'no_nik_penanggung_jawab', 'nama_penanggung_jawab', 'no_hp_penanggung_jawab', 'email_penanggung_jawab', 'alamat_penangung_jawab', 'nama_organisasi', 'judul_acara', 'nama_penceramah', 'foto', 'imam_id', 'jumlah_jamaah', 'infaq', 'proposal_pengajuan', 'status', 'create_at'];

    public function setIdAttribute($value)
    {
        if ($value == null) {
            $lastId = Booking::orderBy('id', 'desc')->pluck('id')->first();

            $newId = substr($lastId, 4) + 1;

            if ($newId < 10) {
                $value = 'BK00' . $newId;
            } else if ($newId < 100) {
                $value = 'BK0' . $newId;
            } else if ($newId < 1000) {
                $value = 'BK' . $newId;
            }
            return $this->attributes['id'] = $value;
        } else {
            return $this->attributes['id'] = $value;
        }
    }
    public function jenisKegiatan()
    {
        return $this->belongsTo('App\Model\Referensi\JenisKegiatan', 'jenis_kegiatan_id');
    }
    public function ruangan()
    {
        return $this->belongsTo('App\Model\Referensi\Ruangan', 'ruangan_id');
    }
}
