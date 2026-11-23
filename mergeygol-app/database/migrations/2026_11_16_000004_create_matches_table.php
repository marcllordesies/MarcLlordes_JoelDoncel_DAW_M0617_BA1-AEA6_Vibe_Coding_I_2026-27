<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('league_id')->constrained()->cascadeOnDelete();
            $table->foreignId('local_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->cascadeOnDelete();
            $table->enum('status', ['scheduled', 'live', 'finished'])->default('scheduled');
            $table->timestamp('start_at')->nullable();
            $table->unsignedSmallInteger('minute')->default(0);
            $table->unsignedSmallInteger('local_goals')->default(0);
            $table->unsignedSmallInteger('away_goals')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
