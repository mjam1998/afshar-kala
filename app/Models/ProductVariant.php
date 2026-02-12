<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected  $fillable = [
      'product_id',
      'size_id',
      'color_id',
      'count'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class);
    }
}
