<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->renameColumn('username_ad', 'username');
            $table->renameColumn('password_ad', 'password');
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->renameColumn('username', 'username_ad');
            $table->renameColumn('password', 'password_ad');
        });
    }
};


