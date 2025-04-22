<?php

namespace App\Modules\AdminAuth\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
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


        static::addGlobalScope('ForAdmin', function (Builder $builder) {
            $builder->where('guard', 'admin');
        });

    }

    public function AauthAcessToken(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

}
