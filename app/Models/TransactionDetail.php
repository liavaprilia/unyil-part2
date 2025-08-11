<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasUuids;
    protected $table = 'transaction_details';
    protected $primaryKey = 'id_transaction_detail';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * Get the transaction that owns the detail.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tdtransaction_id', 'id_transaction');
    }

    /**
     * Get the product associated with the transaction detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'tdproduct_id', 'id_product');
    }

}
