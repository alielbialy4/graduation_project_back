<?php

namespace App\Modules\Rooms\Models;

use Illuminate\Support\Facades\Auth;
use App\Modules\Devices\Models\Devices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Rooms extends Model
{

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('ForRooms', function (Builder $builder) {
    //         $builder->where('user_id', Auth::guard('sanctum')?->user()?->id);
    //     });

    //     static::creating(function (Rooms $Rooms) {
    //         $Rooms->user_id = Auth::guard('sanctum')?->user()?->id;
    //     });
    // }

    protected $table   = 'rooms';
    protected $guarded = [];
    public    $timestamps = true;

    // relation with devices
    public function devices()
    {
        return $this->hasMany(Devices::class, 'room_id', 'id');
    }
}
