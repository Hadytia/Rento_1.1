<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_code', 32)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->date('rental_start');
            $table->date('rental_end');
            $table->integer('total_days')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->string('trx_status', 32)->default('Active');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};