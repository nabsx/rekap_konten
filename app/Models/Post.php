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
}
