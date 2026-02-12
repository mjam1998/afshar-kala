<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
      'order_id',
      'product_id',
      'product_color_id',
        'product_size_id',
        'price',
        'discount',
        'quantity'

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function product_color(){
        return $this->belongsTo(ProductColor::class);
    }
    public function product_size(){
        return $this->belongsTo(ProductSize::class);
    }
}
