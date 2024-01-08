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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('txnNo',20)->unique();
            $table->bigInteger('user_id');
            $table->bigInteger('scheme_id');
            $table->decimal('amount_paid',12,2);
            $table->integer('month_paid');
            $table->enum('status',['pending','active','inactive','rejected'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
