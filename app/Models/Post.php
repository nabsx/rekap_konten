<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'platform_id',
        'user_id',
        'posted_at',
        'title',
        'description',
        'url',
        'content_type',
        'followers',
        'viewers',
        'likes',
        'subscribers',
    ];

    protected $casts = [
        'posted_at' => 'date',
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: filter by month & year
    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('posted_at', $year)
                     ->whereMonth('posted_at', $month);
    }

    // Scope: filter by year
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('posted_at', $year);
    }

    // Platform type helpers
    public function isInstagram()
    {
        return $this->platform && $this->platform->isInstagram();
    }

    public function isReels()
    {
        return $this->isInstagram() && $this->content_type === 'reels';
    }

    public function isPost()
    {
        return $this->isInstagram() && $this->content_type === 'post';
    }

    public function isTiktok()
    {
        return $this->platform && $this->platform->isTiktok();
    }

    public function isX()
    {
        return $this->platform && $this->platform->isX();
    }

    public function isFacebook()
    {
        return $this->platform && $this->platform->isFacebook();
    }

    public function isYoutube()
    {
        return $this->platform && $this->platform->isYoutube();
    }

    public function isWebsite()
    {
        return $this->platform && $this->platform->isWebsite();
    }

    // Get platform display name with content type
    public function getPlatformDisplay()
    {
        $name = $this->platform->name ?? 'Unknown';
        
        if ($this->content_type) {
            $contentTypes = config('platforms.content_types', []);
            $type = $contentTypes[$this->content_type] ?? $this->content_type;
            return "{$name} - {$type}";
        }
        
        return $name;
    }

    // Get metrics as array
    public function getMetrics()
    {
        return array_filter([
            'followers'   => $this->followers,
            'viewers'     => $this->viewers,
            'subscribers' => $this->subscribers,
        ]);
    }
}
