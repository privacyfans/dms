<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidatedByToDetailFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_file', function (Blueprint $table) {
            $table->string('doc_validation_status', 5)->nullable()->after('validation_status_date')->comment('Status validasi dokumen oleh SPV: 1=Valid, 0=Invalid, NULL=Belum divalidasi');
            $table->timestamp('doc_validation_date')->nullable()->after('doc_validation_status')->comment('Tanggal validasi dokumen oleh SPV');
            $table->string('doc_validated_by', 50)->nullable()->after('doc_validation_date')->comment('NIK SPV yang melakukan validasi dokumen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_file', function (Blueprint $table) {
            $table->dropColumn(['doc_validation_status', 'doc_validation_date', 'doc_validated_by']);
        });
    }
}
