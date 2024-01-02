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
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->String('item_code');
            $table->String('unit');
            $table->text('description');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('modal_id')->constrained();
            $table->unsignedBigInteger('dealer_id')->nullable();
            $table->date('expired_date');
            $table->decimal('received_price_per_unit', 8, 2);
            $table->integer('quantity');
            $table->decimal('sale_price_per_unit', 8, 2);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
