<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=[
        'name',
        'photo',
        'slug',
        'meta_description',
        'photo_alt',
        'page_title'
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
