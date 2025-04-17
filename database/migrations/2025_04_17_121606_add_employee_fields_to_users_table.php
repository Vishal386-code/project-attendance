<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('sr_no')->nullable()->after('id');
            $table->string('designation')->nullable()->after('name');
            $table->string('ref_no')->nullable()->after('designation');
            $table->date('date_joined')->nullable()->after('ref_no');
            $table->string('status')->nullable()->after('date_joined');
            $table->string('wfh_hybrid')->nullable()->after('status');
            $table->date('date_left')->nullable()->after('wfh_hybrid');
            $table->string('father_name')->nullable()->after('date_left');
            $table->date('dob')->nullable()->after('father_name');
            $table->text('address')->nullable()->after('dob');
            $table->string('contact_number')->nullable()->after('address');
            $table->date('on_role_date')->nullable()->after('contact_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sr_no',
                'designation',
                'ref_no',
                'date_joined',
                'status',
                'wfh_hybrid',
                'date_left',
                'father_name',
                'dob',
                'address',
                'contact_number',
                'on_role_date',
            ]);
        });
    }
};
