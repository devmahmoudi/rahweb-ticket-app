<?php

namespace App\Models;

use App\Observers\MessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ObservedBy(MessageObserver::class)]
class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [];

    protected $table = 'messages';

    public function chat():BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
