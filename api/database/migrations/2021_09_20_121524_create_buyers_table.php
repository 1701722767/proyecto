<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 30)->unique();
            $table->string('name', 30)->unique();
            $table->boolean('status');
            $table->string('identification',15);
            $table->string('address',30);
            $table->string('phone', 30);
            $table->unsignedBigInteger('identification_type_id');
            $table->foreign('identification_type_id')->references('id')
                                                      ->on('identification_types');
                                                      //TODO:
                                                      
                                                      //validate with client when identification types record is deleted
                                                    //   ->onDelete('cascade');
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
        Schema::dropIfExists('buyers');
    }
}
