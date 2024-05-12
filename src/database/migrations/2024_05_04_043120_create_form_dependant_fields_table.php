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
        Schema::create('form_dependant_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_master_id');
            $table->unsignedBigInteger('master_key');
            $table->unsignedBigInteger('foreign_key');
            $table->unique(['form_master_id','master_key','foreign_key']);
            $table->foreign('form_master_id')->references('id')->on('form_masters')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('form_dependant_fields')->insert(
            array(
            [
                'form_master_id' => 8,
                'master_key' => 7,
                'foreign_key' => 8
            ],
            [
                'form_master_id' => 8,
                'master_key' => 7,
                'foreign_key' => 9
            ]
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_dependant_fields');
    }
};
