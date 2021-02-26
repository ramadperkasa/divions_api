<?php

namespace App\Model\Referensi;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ref_page';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['id', 'judul', 'konten', 'judul_en', 'konten_en', 'meta_tag', 'total_view', 'last_view', 'slug'];

    public function menuSub()
    {
        return $this->hasOne('App\Model\Referensi\MenuSub', 'page_id');
    }

    public function menu()
    {
        return $this->hasOne('App\Model\Referensi\Menu', 'page_id');
    }

    public function setTotalViewAttribute($value)
    {

        if ($value == null) {
            return $this->attributes['total_view'] = 0;
        } else {
            return $this->attributes['total_view'] = $value;
        }
    }
    public function setIdAttribute($value)
    {
        if ($value) {
            $this->attributes['id'] = $value;
        } else {
            $value = Page::orderBy('id', 'DESC')->pluck('id')->first();
            $this->attributes['id'] = $value + 1;
        }
    }
    public function setLastViewAttribute($value)
    {

        if ($value == null) {
            return $this->attributes['last_view'] = '0000-00-00 00:00:00';
        } else {
            return $this->attributes['last_view'] = $value;
        }
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->menu()->delete();
            $query->menuSub()->delete();
        });
    }
}
