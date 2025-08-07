<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'url',
        'is_active',
        'open_for_contributors',
        'contributor_requirements',
        'contributors_needed',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_for_contributors' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    // Helper method to get status display name
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'idea' => 'Idea',
            'designing_mvp' => 'Designing MVP',
            'building_mvp' => 'Building MVP',
            'running_mvp' => 'Running MVP',
            'scaling_mvp' => 'Scaling MVP',
            default => ucfirst($this->status),
        };
    }

    // Get open projects looking for contributors
    public static function getOpenOpportunities($limit = 10)
    {
        return static::where('open_for_contributors', true)
            ->where('is_active', true)
            ->with(['user', 'updates' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
