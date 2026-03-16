<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestNote extends Model
{
    protected $fillable = [
        'session_id',
        'author_name',
        'title',
        'content',
        'theme',
    ];
}
