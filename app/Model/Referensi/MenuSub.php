<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class MenuSub extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_menu_sub';

    public $incrementing = true;

    public $keyType = 'int';

    protected $fillable = ['id', 'parent_id', 'title', 'description', 'title_en', 'description_en', 'url', 'ishide', 'reorder', 'block_template_id', 'kategori_id', 'tipe_link', 'status', 'keterangan', 'ishide_footer'];

    protected $hidden = ['parent', 'page', 'category'];

    protected $appends = ['menu', 'halaman', 'kategori'];

    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = MenuSub::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
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
    public function setReorderAttribute($value)
    {
        $reorder =  MenuSub::orderBy('reorder', 'desc')->pluck('reorder')->first();

        if ($value == null || $value == '') {
            return $this->attributes['reorder'] = $reorder + 1;
        } else {
            return $this->attributes['reorder'] = $value;
        }
    }

    // public function setUrlAttribute($value)
    // {
    //     if (request()->tipe_link != '3') {
    //         $this->attributes['url'] = $value;
    //     } else {
    //         $this->attributes['url'] = '/news/' . $value;
    //     }
    // }

    public function parent()
    {
        return $this->belongsTo('App\Model\Referensi\Menu', 'parent_id');
    }

    public function page()
    {
        return $this->belongsTo('App\Model\Referensi\BlokTemplate', 'block_template_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Model\Referensi\Kategori', 'kategori_id');
    }

    public function getMenuAttribute()
    {
        return $this->parent ? $this->parent : '';
    }

    public function getHalamanAttribute()
    {
        if ($this->page) {
            return $this->page->judul;
        }
    }

    public function getKategoriAttribute()
    {
        if ($this->category) {
            return $this->category->nama;
        }
    }

    public function getUrlAttribute($value)
    {
        if ($this->tipe_link != '3') {
            return $value;
        } else {
            return substr($value, 6);
        }
    }
}
