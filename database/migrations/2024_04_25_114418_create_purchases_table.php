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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->comment('The customer fullname.');
            $table->date('sale_date');
            $table->string('amount_paid');
            $table->string('phone');
            $table->longText('description');
            $table->foreignId('creator_id');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('creator_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
