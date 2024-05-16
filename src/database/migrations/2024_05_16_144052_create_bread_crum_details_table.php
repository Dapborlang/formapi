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
        Schema::create('bread_crum_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bread_crum_id');
            $table->unsignedBigInteger('form_master_id');
            $table->string('on_column_name');
            $table->string('breadcrumb_item');
            $table->foreign('bread_crum_id')->references('id')->on('bread_crums')->onDelete('cascade');
            $table->foreign('form_master_id')->references('id')->on('form_masters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bread_crum_details');
    }
};
