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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id'); // Relación con negocio
            $table->string('client_name');
            $table->string('client_phone');
            $table->text('comment')->nullable();
            $table->integer('rating')->default(5); // estrellas: 1-5
            $table->string('result')->nullable(); // ejemplo: "5 estrellas", "Sin enlace"
            $table->timestamp('review_date')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
    
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
