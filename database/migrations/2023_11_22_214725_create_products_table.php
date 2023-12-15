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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('product_category_id');
            $table->integer('product_image_id');
            $table->decimal('product_actual_price',8,2);
            $table->integer('product_percent');
            $table->decimal('product_selling_price',8,2);
            $table->text('product_desc');
            $table->enum('status',['pending','active','inactive','rejected'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
