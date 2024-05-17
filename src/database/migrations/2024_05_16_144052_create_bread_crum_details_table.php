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
        Schema::create('bread_crumb_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bread_crumb_id');
            $table->unsignedBigInteger('form_master_id');
            $table->string('on_column_name');
            $table->string('breadcrumb_item');
            $table->foreign('bread_crumb_id')->references('id')->on('bread_crumbs')->onDelete('cascade');
            $table->foreign('form_master_id')->references('id')->on('form_masters')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('bread_crumb_details')->insert(
            array(
            [
                'bread_crumb_id' => 1,
                'form_master_id' => 3,
                'on_column_name' => 'form_master_id',
                'breadcrumb_item' => 'Input Type'
            ]
            ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bread_crumb_details');
    }
};
