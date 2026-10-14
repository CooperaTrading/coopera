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
            if (!Schema::hasTable('proformas')) {
                Schema::create('proformas', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('quote_request_id')->constrained()->cascadeOnDelete();
                    $table->string('proforma_number')->unique();
                    $table->date('issue_date');
                    $table->date('validity_date');
                    $table->text('payment_terms')->nullable();
                    $table->text('delivery_time')->nullable();
                    $table->text('bank_details')->nullable();
                    $table->text('notes')->nullable();
                    $table->decimal('subtotal', 12, 2)->default(0);
                    $table->decimal('vat', 12, 2)->default(0);
                    $table->decimal('total', 12, 2)->default(0);
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::dropIfExists('proformas');
    }
};
