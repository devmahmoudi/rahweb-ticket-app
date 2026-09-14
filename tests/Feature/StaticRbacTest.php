<?php

namespace Tests\Feature;

use App\Enums\User\UserType;
use App\Models\Chat;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class StaticRbacTest extends TestCase
{
    public function test_chat_is_visible_only_to_members(): void
    {
        $member = User::factory()->customer()->create();
        $outsider = User::factory()->operator()->create();
        $chat = Chat::factory()->create();
        $chat->members()->attach([$member->id, $outsider->id]);

        $this->actingAs($member);
        $this->assertTrue(Chat::whereKey($chat->id)->exists());

        $chat->members()->detach($outsider);
        $this->actingAs($outsider);
        $this->assertFalse(Chat::whereKey($chat->id)->exists());
    }

    public function test_customer_can_create_tickets_but_operator_cannot(): void
    {
        $customer = User::factory()->customer()->create();
        $operator = User::factory()->operator()->create();

        $this->assertTrue(Gate::forUser($customer)->allows('create', Ticket::class));
        $this->assertFalse(Gate::forUser($operator)->allows('create', Ticket::class));
    }

    public function test_operator_can_view_only_customers_with_an_assigned_ticket(): void
    {
        $operator = User::factory()->operator()->create();
        $assignedCustomer = User::factory()->customer()->create();
        $otherCustomer = User::factory()->customer()->create();

        Ticket::factory()->pending()->operator($operator)->create([
            'user_id' => $assignedCustomer->id,
        ]);

        $this->assertTrue(Gate::forUser($operator)->allows('view', $assignedCustomer));
        $this->assertFalse(Gate::forUser($operator)->allows('view', $otherCustomer));
    }

    public function test_only_assigned_operator_can_close_a_ticket(): void
    {
        $operator = User::factory()->operator()->create();
        $otherOperator = User::factory()->operator()->create();
        $ticket = Ticket::factory()->pending()->operator($operator)->create();

        $this->assertTrue(Gate::forUser($operator)->allows('update', $ticket));
        $this->assertFalse(Gate::forUser($otherOperator)->allows('update', $ticket));
    }

    public function test_superadmin_has_unrestricted_static_access(): void
    {
        $superadmin = User::factory()->state(['type' => UserType::SUPERADMIN->value])->create();
        $ticket = Ticket::factory()->pending()->create();

        $this->assertTrue(Gate::forUser($superadmin)->allows('create', Ticket::class));
        $this->assertTrue(Gate::forUser($superadmin)->allows('update', $ticket));
        $this->assertTrue(Gate::forUser($superadmin)->allows('view', $ticket));
    }
}
