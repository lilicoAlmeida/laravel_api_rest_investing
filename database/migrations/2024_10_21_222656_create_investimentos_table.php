<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestimentosTable extends Migration
{
    public function up()
    {
        Schema::create('investimentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');                     // Nome do ativo
            $table->decimal('saldo_atual', 10, 2);      // Saldo atual do ativo
            $table->decimal('rentabilidade', 5, 2);     // Rentabilidade (%)
            $table->unsignedBigInteger('user_id');      // Relacionamento com usuário
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('investimentos');
    }
}
