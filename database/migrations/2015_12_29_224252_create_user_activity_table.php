<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_activity', function (Blueprint $table) {
            $table->increments('ref_activ');
            $table->text('activ_desc');
            $table->string('ip_address', 45);
            $table->string('mac_address', 70)->nullable();
            $table->text('user_agent');
            $table->timestamp('activ_date');
            $table->unsignedInteger('ref_user');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() != 'sqlite') {
            Schema::table('users_activity', function (Blueprint $table) {
                $table->dropForeign('user_activity_user_id_foreign');
            });
        }

        Schema::drop('users_activity');

        \DB::table('permissions')->where('name', 'users.activity')->delete();
    }
}
