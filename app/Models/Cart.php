<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'carts';
    protected $primaryKey = 'id_cart';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';
    public function user()
    {
        return $this->belongsTo(User::class, 'cart_user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'cart_product_id', 'id_product');
    }
}
