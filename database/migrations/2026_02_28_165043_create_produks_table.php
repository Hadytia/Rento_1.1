<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('product_name', 150);
            $table->text('description')->nullable();
            $table->decimal('rental_price', 15, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('condition', 32)->nullable();
            $table->string('photo', 255)->nullable();
            $table->integer('min_rental_days')->default(1);
            $table->date('created_date')->nullable();
            $table->string('company_code', 32)->nullable();
            $table->smallInteger('status')->default(1);
            $table->smallInteger('is_deleted')->default(0);
            $table->string('created_by', 32)->nullable();
            $table->string('last_updated_by', 32)->nullable();
            $table->timestamp('last_updated_date')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};