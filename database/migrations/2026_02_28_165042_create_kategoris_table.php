<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->string('company_code', 32)->nullable();
            $table->smallInteger('status')->default(1);
            $table->smallInteger('is_deleted')->default(0);
            $table->string('created_by', 32)->nullable();
            $table->timestamp('created_date')->nullable()->useCurrent();
            $table->string('last_updated_by', 32)->nullable();
            $table->timestamp('last_updated_date')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};