<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected  $fillable = [
      'title',
      'slug',
      'description',
      'photo',
      'photo_alt',
      'meta_description',
        'page_title'
    ];
}
