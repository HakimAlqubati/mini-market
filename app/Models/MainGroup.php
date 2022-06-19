<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class MainGroup extends Model
{
    use HasFactory, Translatable;

    protected $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'english_name',
        'description',
        'image'
    ];

    public function subGroups()
    {
        return $this->hasMany(SubGroup::class, 'parent_id');
    }
}
