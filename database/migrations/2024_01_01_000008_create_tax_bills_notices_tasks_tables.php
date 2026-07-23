<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_tax_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->enum('status', ['Draft', 'Published'])->default('Draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->index(['client_id', 'month', 'year']);
        });

        Schema::create('sales_tax_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->string('invoice_no', 100)->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('amount', 18, 2)->default(0);
            $table->decimal('sales_tax', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('sales_tax_returns')->onDelete('cascade');
        });

        Schema::create('sales_tax_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->string('invoice_no', 100)->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('amount', 18, 2)->default(0);
            $table->decimal('input_tax', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('sales_tax_returns')->onDelete('cascade');
        });

        Schema::create('withholding_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->enum('status', ['Draft', 'Published'])->default('Draft');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
        });

        Schema::create('withholding_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->string('tax_type', 100);
            $table->decimal('amount', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('withholding_returns')->onDelete('cascade');
        });

        Schema::create('utility_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('bill_type_id');
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->string('consumer_no', 100)->nullable();
            $table->decimal('amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('bill_type_id')->references('id')->on('utility_bill_types')->onDelete('restrict');
            $table->index(['client_id', 'month', 'year']);
        });

        Schema::create('client_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('document_name', 255);
            $table->string('file_path', 255);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('document_categories')->onDelete('set null');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('notice_number', 100)->nullable();
            $table->date('notice_date')->nullable();
            $table->string('subject', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        Schema::create('notice_replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('notice_id');
            $table->date('reply_date')->nullable();
            $table->text('remarks')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->unsignedBigInteger('replied_by')->nullable();
            $table->timestamps();

            $table->foreign('notice_id')->references('id')->on('notices')->onDelete('cascade');
            $table->foreign('replied_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['assigned_to', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('notice_replies');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('client_documents');
        Schema::dropIfExists('utility_bills');
        Schema::dropIfExists('withholding_details');
        Schema::dropIfExists('withholding_returns');
        Schema::dropIfExists('sales_tax_purchases');
        Schema::dropIfExists('sales_tax_sales');
        Schema::dropIfExists('sales_tax_returns');
    }
};