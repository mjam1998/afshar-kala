<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
   protected $table = 'offers';

   protected $fillable = [
     'code',
     'discount_amount',
       'expires_at',
       'created_at',
       'updated_at',
   ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // چک کردن اینکه کد منقضی نشده باشه
    public function isValid()
    {
        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    // رابطه با سفارشات
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_offers')
            ->withPivot('discount_applied')
            ->withTimestamps();
    }
}
