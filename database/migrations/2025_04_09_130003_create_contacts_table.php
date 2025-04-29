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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('client_name');
            $table->string('email')->nullable();
            $table->string('client_phone');
            $table->text('message')->nullable(); // por si se guarda texto
            $table->timestamp('contacted_at')->nullable();
            $table->timestamps();
    
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
