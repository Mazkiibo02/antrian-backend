<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('queue_number')->unique();
            $table->enum('status', ['waiting', 'active', 'completed', 'cancelled'])->default('waiting');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queues');
    }
}; 