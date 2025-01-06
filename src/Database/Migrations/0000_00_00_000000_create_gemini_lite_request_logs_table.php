<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GeminiLiteRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create('geminilite_request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('geminilite_request_logs');
    }
}

