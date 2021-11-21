<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyerDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_document', function (Blueprint $table) {            
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('document_id');
            $table->foreign('buyer_id')->references('id')
                                               ->on('buyers');                                               
            $table->foreign('document_id')->references('id')
                                               ->on('documents');                                                                                         
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
        Schema::dropIfExists('buyer_document');
    }
}
