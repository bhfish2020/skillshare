<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_category_id',
        'name',
        'description',
    ];

    public function skillCategory()
    {
        return $this->belongsTo(SkillCategory::class);
    }

    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, UserSkill::class, 'skill_id', 'id', 'id', 'user_id');
    }
}
