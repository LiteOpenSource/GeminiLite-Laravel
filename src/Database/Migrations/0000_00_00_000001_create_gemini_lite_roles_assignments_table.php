<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gemini_lite_role_assignments', function (Blueprint $table) {
            $table->id(); // id como PK
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('gemini_lite_roles')->cascadeOnDelete();
            $table->boolean('active');
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('geminilite_roles_assignments');
    }
};

