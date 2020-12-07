<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    //protected $fillable = ['comments'];

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function user() 
    {
        return $this->belongsTo('App\Models\User');
    }

    public function addComment($comment) {
        $this->comments()->create($comment);
    }
}
