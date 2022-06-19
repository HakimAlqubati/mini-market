<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'notes',
        'customer_address',
        'order_state',
        'created_by',
        'total_price'
    ];

    public function order_items()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function customer()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
