<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;


class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'current_state',
        'future_state',
        'status',
    ];

    /**
     * Связь с пользователем
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getStatusTextAttribute(): string
    {
        $statuses = [
            'new' => 'Новое',
            'in_review' => 'Ожидает',
            'accepted' => 'Принято',
            'rejected' => 'Отклонено'
        ];

        return $statuses[$this->status] ?? 'Неизвестный статус';
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProposalFile::class);
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notification::class);
    }

}

