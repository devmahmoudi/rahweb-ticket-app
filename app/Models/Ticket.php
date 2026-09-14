<?php

namespace App\Models;

use App\Events\NewTicket;
use App\Models\Scopes\TicketUserTypeScope;
use App\Observers\TicketObserver;
use App\TicketStateManagement\TicketState;
use App\TicketStateManagement\TicketStateInterface;
use App\TicketStateManagement\States\AcceptedState;
use App\TicketStateManagement\States\DelegatedState;
use App\TicketStateManagement\States\PendingState;
use App\TicketStateManagement\States\RejectedState;
use App\TicketStateManagement\States\WebserviceState;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[ScopedBy(TicketUserTypeScope::class)]
#[ObservedBy(TicketObserver::class)]
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
        return Chat::withoutGlobalScopes()
            ->where('meta', Ticket::class . ",{$this->id}")
            ->first();
    }

    #[Scope]
    protected function state(Builder $query, TicketState $state): void
    {
        $query->where('status', $state->value);
    }

    #[Scope]
    protected function stateNot(Builder $query, TicketState $state): void
    {
        $query->where('status', "!=", $state->value);
    }

    #[Scope]
    protected function cartable(Builder $query):mixed
    {
        return $query->state(TicketState::PENDING)->orWhere->stateNot(TicketState::REJECTED);
    }

    public function stateManagement(): TicketStateInterface
    {
        return match (TicketState::tryFrom($this->status)) {
            TicketState::PENDING => app(PendingState::class, ['ticket' => $this]),
            TicketState::ACCEPTED => app(AcceptedState::class, ['ticket' => $this]),
            TicketState::DELEGATED => app(DelegatedState::class, ['ticket' => $this]),
            TicketState::WEBSERVICE => app(WebserviceState::class, ['ticket' => $this]),
            TicketState::REJECTED => app(RejectedState::class, ['ticket' => $this]),
            default => throw new \UnexpectedValueException("Unknown ticket state: {$this->status}"),
        };
    }
}
