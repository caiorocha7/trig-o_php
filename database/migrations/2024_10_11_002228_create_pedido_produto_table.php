<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoProdutoTable extends Migration
{
    public function up()
    {
        Schema::create('pedido_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->decimal('preco', 10, 2);
            $table->integer('quantidade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_produto');
    }
}
