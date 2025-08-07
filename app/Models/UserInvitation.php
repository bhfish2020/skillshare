<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id', 
        'status',
        'message',
        'responded_at'
    ];

    protected $casts = [
        'responded_at' => 'datetime'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function accept(): void
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now()
        ]);
    }

    public function decline(): void
    {
        $this->update([
            'status' => 'declined',
            'responded_at' => now()
        ]);
    }
}
