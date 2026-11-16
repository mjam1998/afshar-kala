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
        'page_title',
        'is_active',
    ];

    protected $casts = [
      'is_active' => 'boolean',
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {

        static::updated(function ($category) {
            if ($category->wasChanged('is_active')) {
                $category->products()->update([
                    'is_active' => $category->is_active
                ]);
            }
        });

    }
}
