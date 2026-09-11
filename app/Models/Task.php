<?php

namespace App\Models;

use App\Models\Scopes\TaskUserTypeScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[ScopedBy(TaskUserTypeScope::class)]
class Task extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    /**
     * The user who created the task
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The user who receives the task on its cartable
     *
     * @return BelongsTo
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * The task's referral history
     *
     * @return HasMany
     */
    public function referralHistory(): HasMany
    {
        return $this->hasMany(ReferralHistory::class, 'task_id');
    }

    /**
     * All task's messages
     *
     * @return MorphMany
     */
    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable');
    }
}
