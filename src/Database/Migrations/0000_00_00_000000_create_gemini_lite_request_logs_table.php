<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gemini_lite_request_logs', function (Blueprint $table) {
            $table->id(); // id como PK
            $table->foreignId('user_id')->constrained('users');
            $table->string('request_type');
            $table->integer('consumed_tokens');
            $table->boolean('request_successful');
            $table->json('request_data');
            $table->json('response_data');
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('geminilite_request_logs');
    }
};

