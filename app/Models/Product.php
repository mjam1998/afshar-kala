<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'slug',
         'is_special',
        'is_active',
        'description',
        'material',
        'weight',
        'dimension',
        'meta_description',
        'page_title',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function product_comments()
    {
        return $this->hasMany(ProductComment::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function oreder_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
