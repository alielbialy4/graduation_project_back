<?php

namespace App\Modules\Mcp_Users\Models;

use App\Modules\Transactions\Models\Transactions;
use App\Modules\Transactions\Models\TransactionsGlobal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Users extends Model
{
    use SoftDeletes;

    protected $table = 'users';
    protected $guarded = [];
    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('ForUsers', function (Builder $builder) {
            $builder->where('guard', 'user');
        });
    }

    // user balance
    public function balance()
    {
        // sum orders amount
        $OrdersTransactions = TransactionsGlobal::where('user_id', $this->id)->where('type' , 'deposit')->where('status', 'completed')->where('order_id','!=',null)->sum('amount');
        // sum offers amount
        $OffersTransactions = TransactionsGlobal::where('user_id', $this->id)->where('type' , 'offer_designer')->where('status', 'completed')->where('offer_id','!=',null)->sum('amount');
        // sum withdraws amount
        $withdraws = TransactionsGlobal::where('user_id', $this->id)->where('type' , 'withdraw')->where('status', 'completed')->sum('amount');

        return ($OrdersTransactions + $OffersTransactions) - $withdraws;

    }

}
