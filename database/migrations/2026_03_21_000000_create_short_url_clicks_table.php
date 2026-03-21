<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_url_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_url_id')->constrained()->cascadeOnDelete();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1024)->nullable();
            $table->text('referer')->nullable();
            $table->string('request_method', 10);
            $table->string('host')->nullable();
            $table->string('path')->nullable();
            $table->text('query_string')->nullable();
            $table->string('accept_language')->nullable();
            $table->json('headers')->nullable();
            $table->timestamp('clicked_at')->useCurrent();
            $table->timestamps();

            $table->index('clicked_at');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_url_clicks');
    }
};
