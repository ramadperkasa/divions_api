<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_menu';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $keyType = 'int';

    protected $fillable = ['id', 'title', 'description', 'title_en', 'description_en', 'url', 'target', 'ishide', 'reorder', 'block_template_id', 'tipe_link', 'status', 'keterangan', 'ishide_footer'];

    protected $appends = ['halaman'];

    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Menu::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }

    public function setReorderAttribute($value)
    {
        $reorder =  Menu::orderBy('reorder', 'desc')->pluck('reorder')->first();

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
    public function setTipeLinkAttribute($value)
    {
        if ($value == null || $value == '') {
            return $this->attributes['tipe_link'] = 0;
        } else {
            return $this->attributes['tipe_link'] = $value;
        }
    }

    public function getHalamanAttribute()
    {
        if ($this->page) {
            return $this->page->judul;
        }
    }

    public function menuSub()
    {
        return $this->hasMany('App\Model\Referensi\MenuSub', 'parent_id', 'id');
    }

    public function page()
    {
        return $this->belongsTo('App\Model\Referensi\BlokTemplate', 'block_template_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->menuSub()->delete();
        });
    }
}
