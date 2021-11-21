<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_action', function (Blueprint $table) {            
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('action_id');
            $table->foreign('role_id')->references('id')
                                      ->on('roles')                                   
                                      ->onDelete('cascade');
            $table->foreign('action_id')->references('id')
                                      ->on('actions')                                    
                                      ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_action');
    }
}
