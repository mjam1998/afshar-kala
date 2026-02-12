<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendMethod extends Model
{
    protected $fillable = [
      'name',
      'description'
    ];
    public function orders(){
    return $this->hasMany(Order::class);
    }
}
