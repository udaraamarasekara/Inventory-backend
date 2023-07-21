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
        Schema::create('project_parts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->String('name');
            $table->foreignId('project_id')->constrained();
            $table->integer('months');
            $table->decimal('cost', 8, 2);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_parts');
    }
};
