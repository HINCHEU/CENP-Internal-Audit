<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_scores', function (Blueprint $table) {
            $table->decimal('score', 5, 2)->change(); // e.g. 100.00, 55.52
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_scores', function (Blueprint $table) {
            $table->integer('score')->change();
        });
    }
};
