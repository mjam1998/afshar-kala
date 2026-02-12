<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoBanner extends Model
{
    protected $fillable = [
      'title',
      'description',
      'btn_text',
        'video_mp4',
        'video_webm',
        'photo',
        'photo_alt',
        'link',
        'meta_description',
        'page_title'
    ];
}
