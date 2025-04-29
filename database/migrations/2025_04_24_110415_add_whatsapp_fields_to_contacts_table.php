<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('status')->default('pending');
            $table->text('response_text')->nullable();
            $table->string('response_type')->nullable();
            $table->timestamp('whatsapp_sent_at')->nullable();
            $table->timestamp('review_link_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'response_text',
                'response_type',
                'whatsapp_sent_at',
                'review_link_sent_at'
            ]);
        });
    }
};
