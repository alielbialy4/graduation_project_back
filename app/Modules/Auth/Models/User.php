<?php

namespace App\Modules\Auth\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    protected $connection = 'mysql';

    protected static function boot()
    {
        parent::boot();
        static::creating(function (User $User) {
            $User->guard = 'user';
        });

        static::addGlobalScope('ForManager', function (Builder $builder) {
            $builder->where('guard', 'user');
        });

    }

    public function AauthAcessToken(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

}
