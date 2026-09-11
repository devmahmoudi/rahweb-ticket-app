<?php

namespace App\Models;

use App\Events\NewTicket;
use App\Models\Scopes\TicketUserTypeScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[ScopedBy(TicketUserTypeScope::class)]
class Ticket extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    protected $dispatchesEvents = [
        'created' => NewTicket::class
    ];

    /**
     * Specify the workgroup to which the ticket was sent
     *
     * @return BelongsTo
     */
    public function workgroup():BelongsTo
    {
        return $this->belongsTo(Workgroup::class);
    }

    /**
     * Specify the user who open and accept ticket for answer and handling
     *
     * @return BelongsTo
     */
    public function recipient():BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * The customer who created ticket
     *
     * @return BelongsTo
     */
    public function owner():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * All ticket's messages
     *
     * @return MorphMany
     */
    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function chat(): Chat|null
    {
        return Chat::where('meta', Ticket::class . ",{$this->id}")
            ->first();
    }
}
