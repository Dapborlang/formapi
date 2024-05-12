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
        Schema::create('form_exclude_columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_master_id');
            $table->string('name');
            $table->foreign('form_master_id')->references('id')->on('form_masters')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('form_exclude_columns')->insert(
            array(
                [
                    'form_master_id' => 3,
                    'name' => 'id'
                ],

                [
                    'form_master_id' => 3,
                    'name' => 'created_at'
                ],
                [
                    'form_master_id' => 3,
                    'name' => 'updated_at'                    
                ],
                [
                    'form_master_id' => 4,
                    'name' => 'id'
                ],

                [
                    'form_master_id' => 4,
                    'name' => 'created_at'
                ],
                [
                    'form_master_id' => 4,
                    'name' => 'updated_at'                    
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_exclude_columns');
    }
};
