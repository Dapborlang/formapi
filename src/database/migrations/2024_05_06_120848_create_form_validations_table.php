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
        Schema::create('form_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_master_id');
            $table->string('column_name');
            $table->string('validation');
            $table->foreign('form_master_id')->references('id')->on('form_masters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_validations');
    }
};
