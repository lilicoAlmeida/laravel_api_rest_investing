<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacoesTable extends Migration
{
    public function up()
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investimento_id');  // Relacionamento com investimento
            $table->enum('tipo', ['compra', 'venda']);      // Tipo de transação
            $table->decimal('quantidade', 10, 2);           // Quantidade transacionada
            $table->date('data');                           // Data da transação
            $table->timestamps();

            $table->foreign('investimento_id')->references('id')->on('investimentos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transacoes');
    }
}
