<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GeminiLiteDataTable extends Migration
{
    public function up()
    {
        Schema::create('geminilite_data_table', function (Blueprint $table) {
            $table->id();
            $table->string('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('geminilite_data_table');
    }
}

