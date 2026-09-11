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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('This name will display in chat item.');
            $table->string('type')->default(\App\Enums\Chat\ChatType::PV->value);
            $table->text('link')->unique();
            $table->text('meta')->nullable()->comment('Meta data.');
            $table->string('status')->default(\App\Enums\Chat\ChatStatus::ENABLED->value)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
