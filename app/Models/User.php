<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\User\UserType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'role_id',
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
     * Reported bugs
     *
     * @return HasMany
     */
    public function bugs():HasMany
    {
        return $this->hasMany(Bug::class, 'creator_id');
    }

    /**
     * All meeting records that created by the user
     *
     * @return HasMany
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * All purchase records that created by the user
     *
     * @return HasMany
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * All tasks that created by the user.
     *
     * @return HasMany
     */
    public function submitTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    /**
     * All tasks that received in the user cartable.
     *
     * @return HasMany
     */
    public function receivedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'recipient_id');
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
     * The assigned role for user
     *
     * @return BelongsTo
     */
    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class);
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

    /**
     * Check that user type is admin or no
     *
     * @return bool
     */
    public function isAdmin():bool
    {
        return $this->type == UserType::ADMIN->value;
    }

    /**
     * Check that customer type is admin or no
     *
     * @return bool
     */
    public function isCustomer():bool
    {
        return $this->type == UserType::CUSTOMER->value;
    }

    /**
     * Check that operator type is admin or no
     *
     * @return bool
     */
    public function isOperator():bool
    {
        return $this->type == UserType::OPERATOR->value;
    }
}
