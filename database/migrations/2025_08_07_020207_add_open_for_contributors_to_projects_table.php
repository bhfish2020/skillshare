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
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('open_for_contributors')->default(false)->after('is_active');
            $table->text('contributor_requirements')->nullable()->after('open_for_contributors');
            $table->integer('contributors_needed')->default(1)->after('contributor_requirements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['open_for_contributors', 'contributor_requirements', 'contributors_needed']);
        });
    }
};
