<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidoResource\Pages;
use App\Models\Pedido;
use App\Models\Produto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Card de Dados do Cliente
                Card::make()
                    ->schema([
                        TextInput::make('cliente_nome')
                            ->label('Nome do Cliente')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),

                        TextInput::make('contato')
                            ->label('Contato')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),

                        Forms\Components\DatePicker::make('data')
                            ->label('Data do Pedido')
                            ->required()
                            ->columnSpan(6),
                    ])
                    ->columns(12),

                // Card de Produtos do Pedido
                Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('produtos')
                            ->relationship('produtos')
                            ->schema([
                                // Adicionando o campo 'codigo' ao formulário
                                TextInput::make('codigo')
                                    ->label('Código')
                                    ->disabled() // Desativado, pois o código já vem do produto
                                    ->required() // Agora garantimos que o código seja obrigatório
                                    ->columnSpan(3),

                                Select::make('produto_id')
                                    ->label('Produto')
                                    ->options(Produto::all()->pluck('nome', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $produto = Produto::find($state);
                                        if ($produto) {
                                            // Preenchendo os campos automaticamente ao selecionar o produto
                                            $set('codigo', $produto->codigo);
                                            $set('preco', $produto->unidade); // Definido como 'unidade'
                                        }
                                    })
                                    ->columnSpan(4),

                                TextInput::make('preco')
                                    ->label('Preço Unitário')
                                    ->numeric()
                                    ->disabled()
                                    ->reactive() // Preenchido automaticamente
                                    ->columnSpan(4),

                                TextInput::make('quantidade')
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->columnSpan(4),
                            ])
                            ->columns(12) // Deixa os campos em linha única
                            ->label('Produtos do Pedido')
                            ->columnSpan(12),
                    ])
                    ->columns(12),

                // Card de Resumo do Pedido
                Card::make()
                    ->schema([
                        TextInput::make('valor') // Alterado de 'valor_parcial' para 'valor'
                            ->label('Valor Parcial') // Ajuste conforme necessário
                            ->numeric()
                            ->required() // O campo valor é obrigatório
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, $get) {
                                // Recalcula o valor quando algo muda nos produtos
                                $produtos = $get('produtos');
                                $valor = 0;
                        
                                // Verifica se há produtos e realiza o cálculo
                                if ($produtos) {
                                    foreach ($produtos as $produto) {
                                        $valor += ($produto['preco'] ?? 0) * ($produto['quantidade'] ?? 0);
                                    }
                                }
                        
                                // Atualiza o valor no campo
                                $set('valor', $valor);
                            })
                            ->afterStateHydrated(function (callable $set, $state, $get) {
                                // Recalcula o valor na hidratação (quando o formulário é carregado)
                                $produtos = $get('produtos');
                                $valor = 0;
                        
                                // Verifica se há produtos e realiza o cálculo
                                if ($produtos) {
                                    foreach ($produtos as $produto) {
                                        $valor += ($produto['preco'] ?? 0) * ($produto['quantidade'] ?? 0);
                                    }
                                }
                        
                                // Atualiza o valor no campo
                                $set('valor', $valor);
                            })
                            ->columnSpan(6),

                        Select::make('tipo_desconto')
                            ->label('Tipo de Desconto')
                            ->options([
                                'percentual' => 'Porcentagem (%)',
                                'valor_fixo' => 'Valor Fixo (R$)',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === 'percentual') {
                                    $set('desconto_percentual', 0);
                                    $set('desconto_valor', null);
                                } else {
                                    $set('desconto_valor', 0);
                                    $set('desconto_percentual', null);
                                }
                            })
                            ->columnSpan(6),

                        // Campo de desconto percentual
                        TextInput::make('desconto_percentual')
                            ->label('Desconto (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->hidden(fn (callable $get) => $get('tipo_desconto') !== 'percentual')
                            ->reactive()
                            ->columnSpan(6),

                        // Campo de desconto em valor fixo
                        TextInput::make('desconto_valor')
                            ->label('Desconto (R$)')
                            ->numeric()
                            ->minValue(0)
                            ->hidden(fn (callable $get) => $get('tipo_desconto') !== 'valor_fixo')
                            ->reactive()
                            ->columnSpan(6),

                        // Valor do desconto aplicado (calculado automaticamente)
                        TextInput::make('desconto_valor_calculado')
                            ->label('Valor do Desconto Aplicado')
                            ->numeric()
                            ->disabled()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $valor = $get('valor') ?? 0;
                                $descontoPercentual = $get('desconto_percentual') ?? 0;
                                $descontoValor = $get('desconto_valor') ?? 0;

                                if ($get('tipo_desconto') === 'percentual') {
                                    $set('desconto_valor_calculado', $valor * ($descontoPercentual / 100));
                                } else {
                                    $set('desconto_valor_calculado', $descontoValor);
                                }
                            })
                            ->columnSpan(6),

                        // Valor final do pedido
                        TextInput::make('valor_final')
                            ->label('Valor Final')
                            ->numeric()
                            ->disabled()
                            ->reactive()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $valor = $get('valor') ?? 0;
                                $descontoAplicado = $get('desconto_valor_calculado') ?? 0;

                                $set('valor_final', max($valor - $descontoAplicado, 0)); // Evita valor negativo
                            })
                            ->columnSpan(6),
                    ])
                    ->columns(12),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente_nome')->label('Cliente'),
                Tables\Columns\TextColumn::make('contato')->label('Contato'),
                Tables\Columns\TextColumn::make('data')->label('Data do Pedido'),
                Tables\Columns\TextColumn::make('valor')->label('Valor Parcial'), // Alterado 'valor_parcial' para 'valor'
                Tables\Columns\TextColumn::make('valor_final')->label('Valor Final'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedido::route('/create'),
            'edit' => Pages\EditPedido::route('/{record}/edit'),
        ];
    }


    public static function getRelations(): array
    {
    return [
        \App\Filament\Resources\PedidoResource\RelationManagers\ProdutosRelationManager::class,
    ];
    }
}
