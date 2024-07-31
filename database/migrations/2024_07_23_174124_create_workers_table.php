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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->char('code', 20)->nullable()->unique();
            $table->char('document_type', 3)->default('001');
            $table->char('document_number', 20);
            $table->string('name', 100);
            $table->string('paternal_surname', 80)->nullable();
            $table->string('maternal_surname', 80)->nullable();
            $table->unsignedBigInteger('office_id')->nullable(); 
            $table->unsignedBigInteger('condition_id')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->char('type', 3);
            $table->date('birthdate')->nullable();
            $table->char('phone_number', 9)->nullable();
            $table->string('email')->nullable()->unique();
            $table->enum('gender', ['M', 'F', 'O'])->nullable();
            $table->boolean('status')->default(true);
            $table->unique(['document_type', 'document_number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
