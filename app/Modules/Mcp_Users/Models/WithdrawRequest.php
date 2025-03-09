<?php

namespace App\Modules\Mcp_Users\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{

    protected $table = 'withdraw_requests';
    protected $guarded = [];
    public $timestamps = true;

    // user relation
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

}
