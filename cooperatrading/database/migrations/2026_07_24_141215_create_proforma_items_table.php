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
            if (!Schema::hasTable('proforma_items')) {
                Schema::create('proforma_items', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('proforma_id')->constrained()->cascadeOnDelete();
                    $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                    $table->string('product_name');
                    $table->string('unit_of_measure')->default('piece');
                    $table->unsignedInteger('quantity');
                    $table->decimal('unit_price', 12, 2)->default(0);
                    $table->decimal('total_price', 12, 2)->default(0);
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma_items');
    }
};
