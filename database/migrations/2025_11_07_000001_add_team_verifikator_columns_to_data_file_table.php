<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamVerifikatorColumnsToDataFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_file', function (Blueprint $table) {
            // Team Verifikator Level 1 columns
            $table->integer('final_status_verif1')->default(0)->after('final_status_spv3');
            $table->string('user_verif1', 255)->nullable()->after('final_status_verif1');
            $table->datetime('date_flag_verif1')->nullable()->after('user_verif1');

            // Team Verifikator Level 2 columns
            $table->integer('final_status_verif2')->default(0)->after('date_flag_verif1');
            $table->string('user_verif2', 255)->nullable()->after('final_status_verif2');
            $table->datetime('date_flag_verif2')->nullable()->after('user_verif2');

            // File bukti hasil pemeriksaan
            $table->string('file_bukti_verifikator', 255)->nullable()->after('date_flag_verif2');

            // Add index for performance
            $table->index(['final_status_verif1', 'final_status_verif2'], 'idx_verifikator');
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
            $table->dropIndex('idx_verifikator');
            $table->dropColumn([
                'final_status_verif1',
                'user_verif1',
                'date_flag_verif1',
                'final_status_verif2',
                'user_verif2',
                'date_flag_verif2',
                'file_bukti_verifikator'
            ]);
        });
    }
}
