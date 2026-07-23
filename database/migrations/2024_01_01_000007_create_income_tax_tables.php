<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('income_tax_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('tax_year_id');
            $table->enum('return_type', ['Original', 'Revised'])->default('Original');
            $table->enum('return_status', ['Draft', 'Published'])->default('Draft');
            $table->unsignedBigInteger('published_by')->nullable();
            $table->dateTime('published_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('tax_year_id')->references('id')->on('tax_years')->onDelete('restrict');
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['client_id', 'tax_year_id']);
            $table->index('return_status');
        });

        Schema::create('return_income_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->decimal('salary_income', 18, 2)->default(0);
            $table->decimal('business_income', 18, 2)->default(0);
            $table->decimal('property_income', 18, 2)->default(0);
            $table->decimal('capital_gain', 18, 2)->default(0);
            $table->decimal('other_income', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('income_tax_returns')->onDelete('cascade');
        });

        Schema::create('return_tax_credits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->string('description', 255);
            $table->decimal('amount', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('income_tax_returns')->onDelete('cascade');
        });

        Schema::create('return_tax_deducted', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->string('source_name', 255);
            $table->decimal('amount', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('income_tax_returns')->onDelete('cascade');
        });

        Schema::create('wealth_statements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->decimal('opening_assets', 18, 2)->default(0);
            $table->decimal('closing_assets', 18, 2)->default(0);
            $table->decimal('opening_liabilities', 18, 2)->default(0);
            $table->decimal('closing_liabilities', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('income_tax_returns')->onDelete('cascade');
        });

        Schema::create('wealth_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wealth_statement_id');
            $table->string('asset_type', 100);
            $table->text('description')->nullable();
            $table->decimal('amount', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('wealth_statement_id')->references('id')->on('wealth_statements')->onDelete('cascade');
        });

        Schema::create('wealth_liabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wealth_statement_id');
            $table->string('liability_type', 100);
            $table->text('description')->nullable();
            $table->decimal('amount', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('wealth_statement_id')->references('id')->on('wealth_statements')->onDelete('cascade');
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('bank_name', 255);
            $table->string('account_title', 255)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('iban', 50)->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('vehicle_name', 255);
            $table->string('registration_no', 100)->nullable();
            $table->string('model_year', 20)->nullable();
            $table->decimal('purchase_cost', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('property_type', 100);
            $table->text('address')->nullable();
            $table->decimal('purchase_cost', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('wealth_liabilities');
        Schema::dropIfExists('wealth_assets');
        Schema::dropIfExists('wealth_statements');
        Schema::dropIfExists('return_tax_deducted');
        Schema::dropIfExists('return_tax_credits');
        Schema::dropIfExists('return_income_details');
        Schema::dropIfExists('income_tax_returns');
    }
};