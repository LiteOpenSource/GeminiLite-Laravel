<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
         Schema::create('gemini_lite_usage', function (Blueprint $table) {
            $table->id(); // id como PK
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('can_make_requests');
            $table->timestamp('current_day_tracking_start')->nullable();
            $table->timestamp('current_month_tracking_start')->nullable();
            $table->integer('completed_requests_today');
            $table->integer('completed_requests_this_month');
            $table->integer('consumed_tokens_today');
            $table->integer('consumed_tokens_this_month');
            $table->timestamp('last_request_completion_time')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('geminilite_usage');
    }
};

