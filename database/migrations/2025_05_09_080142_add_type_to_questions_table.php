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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type')->default('multiple_choice')->after('quiz_id');
            $table->json('options')->nullable()->after('answers');
            $table->text('explanation')->nullable()->after('correct');
            $table->integer('difficulty')->default(1)->after('explanation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'options', 'explanation', 'difficulty']);
        });
    }
};
