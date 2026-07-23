<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no', 50)->unique();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('tax_year_id')->nullable();
            $table->date('voucher_date');
            $table->date('due_date')->nullable();
            $table->decimal('amount', 18, 2)->default(0);
            $table->decimal('discount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->enum('status', ['Paid', 'Partial', 'Unpaid'])->default('Unpaid');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('tax_year_id')->references('id')->on('tax_years')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['client_id', 'status']);
        });

        Schema::create('voucher_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('voucher_id');
            $table->string('service_name', 255);
            $table->decimal('amount', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('receipt_no', 50)->unique();
            $table->unsignedBigInteger('voucher_id');
            $table->unsignedBigInteger('client_id');
            $table->date('receipt_date');
            $table->decimal('amount', 18, 2)->default(0);
            $table->string('payment_method', 100)->default('Cash');
            $table->string('bank_name', 255)->nullable();
            $table->string('cheque_no', 100)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();

            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('restrict');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['client_id', 'receipt_date']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 18, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['expense_date', 'category_id']);
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->string('reminder_type', 100)->nullable();
            $table->enum('status', ['Active', 'Done'])->default('Active');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('voucher_items');
        Schema::dropIfExists('vouchers');
    }
};