<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class AgendaDetail extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_agenda_detail';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = true;
    protected $fillable = ['id', 'agenda_id', 'jenis_kegiatan_id', 'ruangan_id', 'warna', 'tgl', 'jam_mulai', 'jam_selesai', 'no_nik_penanggung_jawab', 'nama_penanggung_jawab', 'no_hp_penanggung_jawab', 'email_penanggung_jawab', 'alamat_penangung_jawab', 'nama_organisasi', 'judul_acara', 'judul_acara_en', 'foto', 'nama_penceramah', 'imam_id', 'jumlah_jamaah', 'infaq', 'proposal_pengajuan', 'create_at', 'status', 'keterangan', 'ishide', 'id_setting_booking'];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }
    public function imam()
    {
        return $this->belongsTo(Imam::class, 'imam_id');
    }
    public function settingBooking()
    {
        return $this->belongsTo(SettingBooking::class, 'id_setting_booking');
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = AgendaDetail::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  AgendaDetail::orderBy('reorder', 'desc')->pluck('reorder')->first();
        if ($value == null || $value == '') {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }
    public function setIshideAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['ishide'] = 0;
        } else {
            return $this->attributes['ishide'] = $value;
        }
    }
}
