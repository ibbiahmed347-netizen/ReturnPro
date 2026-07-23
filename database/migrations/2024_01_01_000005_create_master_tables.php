<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_years', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tax_year', 10)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->timestamps();
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name', 100);
            $table->timestamps();
        });

        Schema::create('utility_bill_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bill_type', 100);
            $table->timestamps();
        });

        Schema::create('document_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name', 100);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('ntn', 30)->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('document_categories');
        Schema::dropIfExists('utility_bill_types');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('tax_years');
    }
};