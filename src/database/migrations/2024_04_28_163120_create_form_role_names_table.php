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
        Schema::create('form_role_names', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('detail');
            $table->timestamps();
        });

        DB::table('form_role_names')->insert(
            array(
                'role' => 'ADM',
                'detail' => 'ADMIN'
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_role_names');
    }
};
