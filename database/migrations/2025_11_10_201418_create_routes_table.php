<?php
// database/migrations/xxxx_xx_xx_create_routes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
        public function up()
        {
        Schema::create('routes', function (Blueprint $table) {
        $table->id();
        $table->string('code', 10)->unique();
        $table->string('start_place');
        $table->string('end_place');
        $table->time('time_start');
        $table->time('time_end');
        $table->timestamps();
    });

    }

    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
?>
