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
        Schema::create('form_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('role');
            $table->boolean('create')->default(false);
            $table->boolean('view')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
            $table->unique(['user_id', 'role']);
            $table->timestamps();
        });

        DB::table('form_roles')->insert(
            array(
                'user_id' => '1',
                'role' => 'ADM',
                'create'=>'1',
                'view'=>'1',
                'update'=>'1',
                'delete'=>'1'
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_roles');
    }
};
