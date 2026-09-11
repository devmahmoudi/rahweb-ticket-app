<?php

namespace App\Models;

use App\Models\Scopes\ChatUserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy(ChatUserScope::class)]
class Chat extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    /**
     * Chat members may be tow user or group of users
     *
     * @return BelongsToMany
     */
    public function members():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function messages():HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * It will be true when at least one member of the chat is online
     */
    protected function isAnyoneOnline(): Attribute
    {
        return Attribute::make(
            get: function(){
                return $this->members()
                    ->where('users.id', '!=', auth()->id())
                    ->where('users.is_online', '1')
                    ->exists();
            },
        );
    }
}
