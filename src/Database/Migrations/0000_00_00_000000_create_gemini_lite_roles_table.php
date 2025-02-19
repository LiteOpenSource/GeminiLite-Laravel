<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
         Schema::create('gemini_lite_roles', function (Blueprint $table) {
            $table->id(); // id como PK
            $table->string('name');
            $table->string('description');
            $table->integer('daily_request_limit');
            $table->integer('monthly_request_limit');
            $table->integer('daily_token_limit');
            $table->integer('monthly_token_limit');
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('geminilite_roles');
    }
};

