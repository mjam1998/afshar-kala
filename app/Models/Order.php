<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
      'send_method_id',
      'name',
      'mobile',
        'total_amount',
        'pay_amount',
        'track_number',
        'state',
        'city',
        'address',
        'postal_code',
        'is_paid',
        'id_get',
        'trans_id',
        'paid_at',
        'status',
        'send_at',
        'postal_track',
        'offer_id'

    ];

    public function send_method()
    {
        return $this->belongsTo(SendMethod::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
   public function offer()
   {
       return $this->belongsTo(Offer::class);
   }
}
