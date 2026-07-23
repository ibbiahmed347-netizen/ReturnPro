<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_code', 50)->unique();
            $table->string('case_number', 50)->nullable()->unique();
            $table->string('ntn', 20)->nullable();
            $table->string('cnic', 20)->nullable();
            $table->string('name', 150);
            $table->string('father_name', 150)->nullable();
            $table->string('business_name', 255)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('fbr_username', 255)->nullable();
            $table->text('fbr_password')->nullable();
            $table->date('registration_date')->nullable();
            $table->decimal('annual_fee', 15, 2)->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->index('cnic');
            $table->index('ntn');
            $table->index('client_code');
            $table->index('case_number');
            $table->index('status');
        });

        Schema::create('client_businesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('business_name', 255);
            $table->string('business_type', 100)->nullable();
            $table->string('strn', 50)->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        Schema::create('client_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('contact_person', 150);
            $table->string('designation', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        Schema::create('client_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('user_id');
            $table->text('note');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_notes');
        Schema::dropIfExists('client_contacts');
        Schema::dropIfExists('client_businesses');
        Schema::dropIfExists('clients');
    }
};