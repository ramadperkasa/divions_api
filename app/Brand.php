<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Brand extends Authenticatable
{
    use HasRoles, HasMultiAuthApiTokens, Notifiable;

    protected $guard_name = 'brand';
    protected $connection = 'mysql';
    protected $table = 'brand';

    protected $fillable = [
        'id', 'nama', 'email', 'password', 'active', 'role_id','remember_token'
    ];

    protected $appends = ['all_permissions'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'roles'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function scopeId($query)
    {
        $lastId = $query->orderBy("id", "DESC")->pluck("id")->first();
        $newId = $lastId + 1;

        return $newId;
    }

    public function findForPassport($username)
    {
        return $this->where('active', 1)->where('email', $username)->first();
    }

    public function getAllPermissionsAttribute()
    {
        $permissions = [];

        $permissions = auth('brand')->user()->getAllPermissions()->pluck('name');

        return $permissions;
    }

    public function brand()
    {
        return $this->belongsTo('App\Model\Referensi\Brand', 'brand_id', '_id');
    }
}
