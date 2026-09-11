<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReferralHistory extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    /**
     * The relevant task.
     *
     * @return BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * The destination user of the referral
     *
     * @return HasOne
     */
    public function destination(): HasOne
    {
        return $this->hasOne(User::class, 'destination_id');
    }
}
