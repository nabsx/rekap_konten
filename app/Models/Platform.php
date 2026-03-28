<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'color'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
