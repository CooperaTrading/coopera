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
            if (!Schema::hasTable('quote_request_items')) {
                Schema::create('quote_request_items', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('quote_request_id')->constrained()->cascadeOnDelete();
                    $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                    $table->string('product_name');
                    $table->string('unit_of_measure')->default('piece');
                    $table->unsignedInteger('quantity');
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_request_items');
    }
};
