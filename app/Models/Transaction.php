<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuids;

    protected $table = 'transactions';
    protected $primaryKey = 'id_transaction';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'transaction_user_id', 'id');
    }

    /**
     * Get the details for the transaction.
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'tdtransaction_id', 'id_transaction');
    }
}
