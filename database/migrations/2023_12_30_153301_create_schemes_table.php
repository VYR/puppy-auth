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
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scheme_type_id');
            $table->decimal('total_amount',12,2)->default(0);
            $table->decimal('amount_per_month',12,2);
            $table->integer('no_of_months');
            $table->integer('coins')->default(0);
            $table->enum('status',['pending','active','inactive','rejected'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
