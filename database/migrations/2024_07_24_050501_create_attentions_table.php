<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 001 Student
     * 002 Professor
     * 003 Administrative / Worker / Obras
     * 004 External
     */
    public function up(): void
    {
        Schema::create('attentions', function (Blueprint $table) {
            $table->id();
            $table->string('type_person');
            $table->string('report_number');
            $table->text('description');
            $table->string('derivations')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('type_attention_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('type_attention_id')->references('id')->on('type_attentions');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attentions');
    }
};
