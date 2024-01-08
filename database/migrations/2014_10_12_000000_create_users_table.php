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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('userName')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('entitlement')->nullable();
            $table->string('email',500)->nullable();
            $table->string('userId',9)->unique();
            $table->string('introducedBy',9)->nullable();            
            $table->string('aadhar',12)->nullable();
            $table->string('pan',10)->nullable();
            $table->string('mobilePhone')->nullable();           
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status',['pending','active','inactive','rejected'])->default('active');
            $table->enum('role',['Admin','Scheme Member','Promoter','Employee'])->default('user');
            $table->integer('userType')->default(0);
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
