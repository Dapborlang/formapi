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
        Schema::create('form_foreign_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_master_id');
            $table->string('master_model');
            $table->string('foreign_key');
            $table->string('master_id');
            $table->string('master_detail');
            $table->unique(['form_master_id','master_model','foreign_key','master_id','master_detail']);
            $table->foreign('form_master_id')->references('id')->on('form_masters')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('form_foreign_keys')->insert(
            array(
            [
                'form_master_id' => 3,
                'master_model' => 'Rdmarwein\FormApi\Models\FormRole',
                'foreign_key' => 'role',
                'master_id' => 'role',
                'master_detail'=>'role'
            ],
            [
                'form_master_id' => 4,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => 5,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => '5',
                'master_model' => 'Rdmarwein\FormApi\Models\InputType',
                'foreign_key' => 'input_type',
                'master_id' => 'input_type',
                'master_detail'=>'input_detail'
            ],
            [
                'form_master_id' => 6,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => 7,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => 8,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => 8,
                'master_model' => 'Rdmarwein\FormApi\Models\FormForeignKey',
                'foreign_key' => 'master_key',
                'master_id' => 'id',
                'master_detail'=>'foreign_key'
            ],
            [
                'form_master_id' => 8,
                'master_model' => 'Rdmarwein\FormApi\Models\FormForeignKey',
                'foreign_key' => 'foreign_key',
                'master_id' => 'id',
                'master_detail'=>'foreign_key'
            ],
            [
                'form_master_id' => 9,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => 10,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => 11,
                'master_model' => 'Rdmarwein\FormApi\Models\FormMaster',
                'foreign_key' => 'form_master_id',
                'master_id' => 'id',
                'master_detail'=>'header'
            ],
            [
                'form_master_id' => 11,
                'master_model' => 'Rdmarwein\FormApi\Models\BreadCrumb',
                'foreign_key' => 'bread_crumb_id',
                'master_id' => 'id',
                'master_detail'=>'breadcrumb_item'
            ]
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_foreign_keys');
    }
};
