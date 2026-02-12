<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoBanner extends Model
{
    protected  $fillable = [
      'title',
      'description',
      'photo',
        'photo_alt',
        'link'
    ];

}
