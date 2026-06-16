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
        Schema::create('plays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->index();
            $table->foreignIdFor(\App\Models\Season::class);
            $table->integer('season_sort')->index()->nullable();
            $table->string('period')->index()->nullable();
            $table->string('venue')->index()->nullable();
            $table->string('playwright')->index()->nullable();
            $table->string('date_start')->nullable();
            $table->string('date_end')->nullable();
            $table->text('summary')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plays');
    }
};
