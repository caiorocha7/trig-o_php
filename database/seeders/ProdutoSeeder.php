<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        // Caminho para o arquivo JSON
        $jsonPath = storage_path('app/produtos_trigao.json');

        // Verificar se o arquivo existe
        if (file_exists($jsonPath)) {
            // Ler o conteúdo do arquivo JSON
            $jsonData = file_get_contents($jsonPath);
            $produtos = json_decode($jsonData, true);

            // Percorrer cada produto e salvar no banco de dados
            foreach ($produtos as $produto) {
                Produto::create([
                    'codigo' => $produto['codigo'],
                    'nome' => $produto['produto'],
                    'secao' => $produto['secao'],
                    'preco' => $produto['preco'],
                    'quantidade' => $produto['quantidade'],
                    'unidade' => $produto['unidade'],
                ]);
            }
        } else {
            $this->command->info("Arquivo JSON não encontrado: $jsonPath");
        }
    }
}
