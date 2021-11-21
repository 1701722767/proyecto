<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyerModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_module', function (Blueprint $table) {            
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('id')
                                      ->on('modules');                                     

            $table->foreign('buyer_id')->references('id')
                                        ->on('buyers');                                       
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
        Schema::dropIfExists('buyer_module');
    }
}
