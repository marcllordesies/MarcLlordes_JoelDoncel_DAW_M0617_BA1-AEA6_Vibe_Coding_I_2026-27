<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table): void {
            $table->string('code', 3)->nullable()->after('name');
        });

        Schema::table('matches', function (Blueprint $table): void {
            $table->unsignedTinyInteger('matchday')->nullable()->after('away_team_id');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table): void {
            $table->dropColumn('matchday');
        });

        Schema::table('teams', function (Blueprint $table): void {
            $table->dropColumn('code');
        });
    }
};
