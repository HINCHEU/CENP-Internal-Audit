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
        Schema::table('evaluation_scores', function (Blueprint $table) {
            $table->string('evaluator_name')->nullable()->after('user_id');
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_scores', function (Blueprint $table) {
            $table->dropColumn('evaluator_name');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
