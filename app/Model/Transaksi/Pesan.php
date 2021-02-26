<?php

namespace App\Model\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'trn_pesan';

    protected $primaryKey = 'id';

    public $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['id', 'from_id', 'to_id', 'pesan_judul', 'pesan_isi', 'tgl_kirim', 'tgl_dibaca', 'jam_kirim', 'jam_baca'];

    protected $appends = ['from', 'to', 'from_first_letter', 'to_first_letter', 'short_title', 'short_content', 'identitas_administrasi'];

    protected $hidden = [
        'fromAnggota', 'fromPendaftar', 'toAnggota', 'toPendaftar'
    ];

    public function fromAnggota()
    {
        return $this->belongsTo('App\Model\Referensi\Anggota', 'from_id');
    }

    public function fromPendaftar()
    {
        return $this->belongsTo('App\Model\Referensi\Pendaftar', 'from_id');
    }

    public function setPesanAttribute($value)
    {
        $isorder =  PelatihanRundown::orderBy('isorder', 'desc')->pluck('isorder')->first();

        if ($value == null || $value == '') {
            return $this->attributes['isorder'] = $isorder + 1;
        } else {
            return $this->attributes['isorder'] = $value;
        }
    }

    public function toAnggota()
    {
        return $this->belongsTo('App\Model\Referensi\Anggota', 'to_id');
    }

    public function toPendaftar()
    {
        return $this->belongsTo('App\Model\Referensi\Pendaftar', 'to_id');
    }

    public function getFromAttribute()
    {
        return $this->fromAnggota ? $this->fromAnggota : $this->fromPendaftar;
    }

    public function getToAttribute()
    {
        return $this->toAnggota ? $this->toAnggota : $this->toPendaftar;
    }

    public function setPesanJudulAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['pesan_judul'] = 'Tanpa Judul';
        } else {
            return $this->attributes['pesan_judul'] = $value;
        }
    }

    public function getFromFirstLetterAttribute()
    {
        return $this->fromAnggota ? substr($this->fromAnggota->nama_lengkap, 0, 1) : substr($this->fromPendaftar->nama_lengkap, 0, 1);
    }

    public function getToFirstLetterAttribute()
    {
        return $this->toAnggota ? substr($this->toAnggota->nama_lengkap, 0, 1) : substr($this->toPendaftar->nama_lengkap, 0, 1);
    }

    public function getShortTitleAttribute()
    {
        $length = strlen($this->pesan_judul);

        return $length > 34 ? substr($this->pesan_judul, 0, 34) . '...' : $this->pesan_judul;
    }

    public function getShortContentAttribute()
    {
        $length = strlen($this->pesan_isi);

        return $length > 67 ? substr($this->pesan_isi, 0, 67) . '...' : $this->pesan_isi;
    }

    public function getIdentitasAdministrasiAttribute()
    {
        $from = $this->fromAnggota ? $this->fromAnggota : $this->fromPendaftar;

        return $from->identitasKelurahan->nama_kelurahan . ', ' . $from->identitasKecamatan->nama_kecamatan . ', ' . $from->identitasKabupaten->nama_kabupaten . ', ' . $from->identitasProvinsi->nama_provinsi;
    }
}
