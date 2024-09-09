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
        Schema::create('satisfaction_surveys', function (Blueprint $table) {
            $table->id();
            $table->char('person_type', 3);
            $table->integer('score')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('attention_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('person_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('attention_id')->references('id')->on('attentions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satisfaction_surveys');
    }
};
