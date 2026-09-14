<?php

namespace App\TicketStateManagement;

use App\Models\User;

interface TicketStateInterface
{
    /**
     * Accept responsibility of a ticket
     *
     * @param User $actor
     * @return void
     */
    public function claim(User $actor): void;

    /**
     * Delegate ticket from the $actor user to the $target user
     *
     * @param User $actor
     * @param User $target
     * @return void
     */
    public function delegateTo(User $actor, User $target): void;

    /**
     * Send ticket to webservice
     *
     * @param User $actor
     * @return void
     */
    public function publishToWebService(User $actor): void;

    /**
     * Reject ticket
     *
     * @return void
     */
    public function reject(): void;
}
