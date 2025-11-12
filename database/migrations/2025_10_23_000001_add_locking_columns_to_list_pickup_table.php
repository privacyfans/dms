<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLockingColumnsToListPickupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_pickup', function (Blueprint $table) {
            $table->string('nik', 50)->nullable()->after('status')->comment('NIK user yang sedang mengakses');
            $table->string('locked_by_level', 20)->nullable()->after('nik')->comment('Level SPV yang lock (spv1/spv2/spv3)');
            $table->timestamp('locked_at')->nullable()->after('locked_by_level')->comment('Waktu lock dibuat');
            $table->timestamp('expired_at')->nullable()->after('locked_at')->comment('Waktu lock akan expire (15 menit dari locked_at)');

            // Add indexes for better performance
            $table->index(['status', 'expired_at'], 'idx_status_expired');
            $table->index(['loan_app_no', 'status'], 'idx_loan_status');
            $table->index('nik', 'idx_nik');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_pickup', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_status_expired');
            $table->dropIndex('idx_loan_status');
            $table->dropIndex('idx_nik');

            // Drop columns
            $table->dropColumn(['nik', 'locked_by_level', 'locked_at', 'expired_at']);
        });
    }
}
