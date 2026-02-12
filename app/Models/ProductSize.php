<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = [
      'product_id',
      'name'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }
}
