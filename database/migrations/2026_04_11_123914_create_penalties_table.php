<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
            $table->string('penalty_type', 50)->nullable();
            $table->decimal('penalty_amount', 15, 2)->default(0);
            $table->integer('overdue_days')->default(0);
            $table->text('description')->nullable();
            $table->smallInteger('resolved')->default(0);
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
        Schema::dropIfExists('penalties');
    }
};