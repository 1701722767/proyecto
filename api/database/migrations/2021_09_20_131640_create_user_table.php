<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();            
            $table->string('password');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->foreign('role_id')->references('id')
                                      ->on('roles')                                      
                                      ->onDelete('set null');

            $table->foreign('buyer_id')->references('id')
                                        ->on('buyers');
                                        // TODO:
                                        
                                        //validate with client, what is doing when a buyer is deleted
                                        // ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
