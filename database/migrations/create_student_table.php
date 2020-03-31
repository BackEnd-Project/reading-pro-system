<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_email',32)->default('')->comment('用户登录名：企业邮箱');
            $table->string('user_password',32)->default('')->comment('用户密码，初始值为企业邮箱');
            $table->ipAddress('user_ip')->default('')->comment('用户最后一次登录ip');
            $table->integer('user_login_cnt')->default(0)->comment('用户登录次数');
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
        Schema::dropIfExists('student');
    }
}
