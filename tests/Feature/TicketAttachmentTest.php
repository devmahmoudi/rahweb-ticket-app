<?php

namespace Tests\Feature;

use App\Livewire\Ticket\Create;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workgroup;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TicketAttachmentTest extends TestCase
{
    public function test_customer_can_create_ticket_with_attachment_and_initial_message_contains_link(): void
    {
        $customer = User::factory()->customer()->create();
        $workgroup = Workgroup::factory()->create();

        $this->actingAs($customer);

        $attachment = UploadedFile::fake()->create('attachment.pdf', 20);

        Livewire::test(Create::class)
            ->set('title', 'Test ticket title')
            ->set('workgroup_id', $workgroup->id)
            ->set('description', 'Test ticket description')
            ->set('attachment', $attachment)
            ->call('store');

        $ticket = Ticket::query()->where('user_id', $customer->id)->firstOrFail();

        $this->assertNotNull($ticket->attachment_path);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'attachment_path' => $ticket->attachment_path,
        ]);

        $this->assertTrue(Storage::disk('public')->exists($ticket->attachment_path));

        $message = $ticket->chat()->messages()->firstOrFail();
        $this->assertStringContainsString(Storage::url($ticket->attachment_path), $message->body);
    }

    public function test_ticket_attachment_requires_pdf_or_image_mime_types(): void
    {
        $customer = User::factory()->customer()->create();
        $workgroup = Workgroup::factory()->create();

        $this->actingAs($customer);

        Livewire::test(Create::class)
            ->set('title', 'Invalid attachment ticket')
            ->set('workgroup_id', $workgroup->id)
            ->set('description', 'Test ticket description')
            ->set('attachment', UploadedFile::fake()->create('file.txt', 20))
            ->call('store')
            ->assertHasErrors(['attachment']);
    }
}
