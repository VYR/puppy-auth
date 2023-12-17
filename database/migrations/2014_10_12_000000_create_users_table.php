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
            $table->string('email',500)->unique();
            $table->string('userId',9)->unique();
            $table->string('introducedBy',9)->nullable(false);            
            $table->string('aadhar',12)->unique();
            $table->string('pan',10)->unique();
            $table->string('mobilePhone')->unique();            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status',['pending','active','inactive','rejected'])->default('active');
            $table->enum('role',['admin','user','dealer'])->default('user');
            $table->enum('userType',[0,1,2])->default(0);
            
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
