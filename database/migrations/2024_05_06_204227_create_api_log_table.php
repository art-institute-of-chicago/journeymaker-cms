<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('datahub_id');
            $table->string('field');
            $table->text('value')->nullable();
            $table->string('hash')->unique();
            $table->timestamps();
        });
    }
};
