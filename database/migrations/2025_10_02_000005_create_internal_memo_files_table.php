<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalMemoFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_memo_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internal_memo_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('internal_memo_id')
                ->references('id')
                ->on('internal_memo')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_memo_files');
    }
}
