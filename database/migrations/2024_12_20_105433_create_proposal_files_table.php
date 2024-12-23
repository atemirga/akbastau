<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('proposal_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade'); // Связь с предложением
            $table->string('file_path'); // Путь к файлу
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proposal_files');
    }

};
