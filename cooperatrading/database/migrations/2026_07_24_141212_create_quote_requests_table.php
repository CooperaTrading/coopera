<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::disableForeignKeyConstraints();
            if (!Schema::hasTable('quote_requests')) {
                Schema::create('quote_requests', function (Blueprint $table) {
                    $table->id();
                    $table->string('customer_name');
                    $table->string('company_name')->nullable();
                    $table->string('email');
                    $table->string('phone')->nullable();
                    $table->text('message')->nullable();
                    $table->enum('status', ['pending', 'processed'])->default('pending');
                    $table->timestamps();

                    $table->index('status');
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
