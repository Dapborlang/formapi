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
        Schema::create('input_types', function (Blueprint $table) {
            $table->id();
            $table->string('input_type')->unique();
            $table->string('input_detail')->unique();
            $table->timestamps();
        });

        DB::table('input_types')->insert(
            array(
            [
                'input_type' => 'text',
                'input_detail' => 'Text'
            ],
            [
                'input_type' => 'date',
                'input_detail' => 'Date'
            ],
            [
                'input_type' => 'textarea',
                'input_detail' => 'Text Area'
            ],
            [
                'input_type' => 'number',
                'input_detail' => 'Number'
            ],
            [
                'input_type' => 'email',
                'input_detail' => 'Email'
            ],
            [
                'input_type' => 'month',
                'input_detail' => 'Month'
            ]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_types');
    }
};
