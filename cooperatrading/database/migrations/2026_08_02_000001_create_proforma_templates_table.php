<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('proforma_templates')) {
            Schema::create('proforma_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('Default VAT Proforma Template');
                $table->boolean('is_active')->default(true);
                $table->json('sections');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma_templates');
    }
};
