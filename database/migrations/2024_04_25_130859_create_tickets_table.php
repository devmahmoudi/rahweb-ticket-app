<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->foreignId('workgroup_id')->constrained();
            $table->foreignId('recipient_id')->nullable()
                ->comment('The ID of the user receiving the ticket.
                    Initially, this field is empty, and for example, when the user opens the ticket, his ID is set to this field.');
            $table->foreignId('user_id')->constrained()->comment('Ticket owner.');
            $table->string('status')->default(\App\Enums\Ticket\TicketStatus::WAITING->value)->nullable();
            $table->timestamps();

            $table->foreign('recipient_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
