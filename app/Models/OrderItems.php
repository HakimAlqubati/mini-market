<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id ',
        'unit_id ',
        'order_id ',
        'qty',
        'price',
        'color_id',
        'created_at',
        'updated_at'
    ];

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
