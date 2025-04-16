<?php

namespace App\Modules\Devices\Models;

use App\Modules\Rooms\Models\Rooms;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Devices extends Model
{

    protected $table   = 'devices';
    protected $guarded = [];
    public    $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ForDevices', function (Builder $builder) {
            $builder->where('user_id', Auth::guard('sanctum')?->user()?->id);
        });

        static::creating(function (Devices $Devices) {
            $Devices->user_id = Auth::guard('sanctum')?->user()?->id;
        });
    }

    public function rooms()
    {
        return $this->belongsTo(Rooms::class, 'room_id', 'id');
    }
}
