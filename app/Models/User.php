<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\User\UserType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    /**
     * All user workgroups
     *
     * @return BelongsToMany
     */
    public function workgroups(): BelongsToMany
    {
        return $this->belongsToMany(Workgroup::class);
    }

    /**
     * All tickets that received in the user cartable.
     *
     * @return HasMany
     */
    public function receivedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'recipient_id');
    }

    /**
     * All media that uploaded with user
     *
     * @return HasMany
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Customer record of this user if its type is customer and customer_id not null
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * User's chats
     *
     * @return BelongsToMany
     */
    public function chats():BelongsToMany
    {
        return $this->belongsToMany(Chat::class);
    }

    public function isSuperadmin(): bool
    {
        return $this->type == UserType::SUPERADMIN->value;
    }

    public function isCustomer():bool
    {
        return $this->type == UserType::CUSTOMER->value;
    }

    public function isOperator():bool
    {
        return $this->type == UserType::OPERATOR->value;
    }
}
