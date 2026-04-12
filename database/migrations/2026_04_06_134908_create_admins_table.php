<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('role', 32)->default('admin');
            $table->string('avatar', 255)->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('last_login_ip')->nullable();
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
        Schema::dropIfExists('admins');
    }
};