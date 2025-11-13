<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReadyToDisbursToDataFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_file', function (Blueprint $table) {
            $table->tinyInteger('ready_to_disburs')->default(0)->after('file_bukti_verifikator')->comment('0=Pending Disbursement, 1=Ready to Disburse');
            $table->string('ready_to_disburs_by', 50)->nullable()->after('ready_to_disburs')->comment('NIK SPV yang flag ready to disburse');
            $table->timestamp('ready_to_disburs_at')->nullable()->after('ready_to_disburs_by')->comment('Timestamp when flagged');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_file', function (Blueprint $table) {
            $table->dropColumn(['ready_to_disburs', 'ready_to_disburs_by', 'ready_to_disburs_at']);
        });
    }
}
