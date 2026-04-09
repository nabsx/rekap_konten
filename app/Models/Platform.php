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

    // Helper methods to check platform type
    public function isX()
    {
        return $this->slug === 'x';
    }

    public function isFacebook()
    {
        return $this->slug === 'facebook';
    }

    public function isTiktok()
    {
        return $this->slug === 'tiktok';
    }

    public function isInstagram()
    {
        return $this->slug === 'instagram';
    }

    public function isYoutube()
    {
        return $this->slug === 'youtube';
    }

    public function isWebsite()
    {
        return $this->slug === 'website';
    }

    // Generic checker
    public function is($slug)
    {
        return $this->slug === $slug;
    }

    // Get all available platforms from config
    public static function getAvailable()
    {
        return config('platforms.available', []);
    }

    // Get platform slug list
    public static function getSlugs()
    {
        return array_keys(self::getAvailable());
    }
}
