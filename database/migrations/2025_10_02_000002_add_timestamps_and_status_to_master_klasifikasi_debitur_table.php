<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsAndStatusToMasterKlasifikasiDebiturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_klasifikasi_debitur', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('klasifikasi_debitur');
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
        Schema::table('master_klasifikasi_debitur', function (Blueprint $table) {
            $table->dropColumn(['status', 'created_at', 'updated_at']);
        });
    }
}
