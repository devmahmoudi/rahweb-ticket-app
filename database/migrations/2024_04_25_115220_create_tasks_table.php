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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->string('status')->default(\App\Enums\Task\TaskStatus::SENT->value)->nullable();
            $table->foreignId('creator_id');
            $table->foreignId('recipient_id')->comment('کاربر');
            $table->timestamps();

            $table->foreign('creator_id')->on('users')->references('id');
            $table->foreign('recipient_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
