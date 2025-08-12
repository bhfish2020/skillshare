<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'job_title',
        'bio',
        'profile_picture',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function skillsCanShare()
    {
        return $this->userSkills()->where('type', 'can_share')->with('skill');
    }

    public function skillsNeed()
    {
        return $this->userSkills()->where('type', 'need')->with('skill');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function activeProject()
    {
        return $this->hasOne(Project::class)->where('is_active', true);
    }

    public function archivedProjects()
    {
        return $this->hasMany(Project::class)->where('is_active', false);
    }

    // Invitation relationships
    public function sentInvitations()
    {
        return $this->hasMany(UserInvitation::class, 'sender_id');
    }

    public function receivedInvitations()
    {
        return $this->hasMany(UserInvitation::class, 'receiver_id');
    }

    public function connections()
    {
        return $this->belongsToMany(User::class, 'user_invitations', 'sender_id', 'receiver_id')
            ->wherePivot('status', 'accepted')
            ->withPivot('responded_at')
            ->withTimestamps();
    }

    // Helper method to find people matching skills you need
    public function findSkillMatches($limit = 5)
    {
        $myNeededSkills = $this->skillsNeed()->pluck('skill_id')->toArray();
        
        if (empty($myNeededSkills)) {
            return collect();
        }

        return User::where('id', '!=', $this->id)
            ->whereHas('userSkills', function($query) use ($myNeededSkills) {
                $query->where('type', 'can_share')
                      ->whereIn('skill_id', $myNeededSkills);
            })
            ->whereDoesntHave('sentInvitations', function($query) {
                $query->where('receiver_id', $this->id);
            })
            ->whereDoesntHave('receivedInvitations', function($query) {
                $query->where('sender_id', $this->id);
            })
            ->with(['userSkills.skill', 'activeProject'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
