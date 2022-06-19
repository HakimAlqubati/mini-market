<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'is_new',
        'description',
        'group_id',
        'code',
        'english_name',
    ];
    public function unitPrices()
    {
        return $this->hasMany(UnitPrice::class);
    }

    public function group()
    {
        return $this->belongsTo(SubGroup::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItems::class);
    }
}
