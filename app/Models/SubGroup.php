<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class SubGroup extends Model
{
    use HasFactory, Translatable;

    protected $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'english_name',
        'description',
    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'group_id');
    }
}
