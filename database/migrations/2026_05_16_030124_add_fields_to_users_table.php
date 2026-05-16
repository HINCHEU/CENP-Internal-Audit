<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_code')->unique()->nullable()->after('id');
            $table->string('gender')->nullable()->after('name');
            $table->enum('role', ['admin', 'super_user', 'normal_user'])->default('normal_user')->after('gender');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete()->after('role');
            $table->string('phone_number')->nullable()->after('email');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['user_code', 'gender', 'role', 'department_id', 'phone_number', 'status']);
        });
    }
};
