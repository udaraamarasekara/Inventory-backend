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
        Schema::create('user_abilities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('profession_id')->constrained();
            $table->string('controller');
            $table->string('method');
            $table->boolean('ability');
            $table->softDeletes($column = 'deleted_at', $precision = 0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_abilities');
    }
};
