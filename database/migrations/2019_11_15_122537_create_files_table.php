<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('file', 500);
            $table->integer('document_id')->unsigned();
            $table->integer('file_type_id')->unsigned();
            $table->unsignedBigInteger('created_by');
            $table->text('custom_fields')->nullable();
            $table->timestamps();
            $table->foreign('document_id')->references('id')->on('documents')
                ->onDelete('cascade');
            $table->foreign('file_type_id')->references('id')->on('file_types');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('files');
    }
}
