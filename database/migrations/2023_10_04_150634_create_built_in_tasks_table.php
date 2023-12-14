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
        Schema::create('built_in_tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->String('name');
            $table->text('description');
            $table->integer('average_population');
            $table->string('average_duration');
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('built_in_tasks');
    }
};
