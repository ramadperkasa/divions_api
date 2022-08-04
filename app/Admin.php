<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasRoles, HasMultiAuthApiTokens, Notifiable;

    protected $guard_name = 'admin';
    protected $connection = 'mysql';
    protected $table = 'admin';

    protected $fillable = [
        'id', 'nama', 'email', 'password', 'active', 'role_id', 'remember_token'
    ];

    protected $appends = ['all_permissions'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
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

        $permissions = auth('admin')->user()->getAllPermissions()->pluck('name');

        return $permissions;
    }

    public function admin()
    {
        return $this->hasMany('App\Model\Transaksi\Berita', 'posted_by');
    }
}
