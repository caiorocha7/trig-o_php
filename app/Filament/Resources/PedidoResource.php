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
                                Select::make('produto_id')
                                    ->label('Produto')
                                    ->options(Produto::all()->pluck('nome', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $produto = Produto::find($state);
                                        if ($produto) {
                                            $set('preco', $produto->preco); // Preenche com o preço do produto
                                        } else {
                                            $set('preco', null); // Reseta o preço se não encontrar
                                        }
                                    })
                                    ->columnSpan(4),

                                TextInput::make('preco')
                                    ->label('Preço Unitário')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(4)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $quantidade = $get('quantidade') ?? 0;
                                        $valorParcial = $state * $quantidade;
                                        $set('valor_parcial', $valorParcial);
                                    }),

                                TextInput::make('quantidade')
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(4)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $preco = $get('preco') ?? 0;
                                        $valorParcial = $preco * $state;
                                        $set('valor_parcial', $valorParcial);
                                    }),
                            ])
                            ->columns(12) // Deixa os campos em linha única
                            ->label('Produtos do Pedido')
                            ->columnSpan(12),
                    ])
                    ->columns(12),

                // Card de Resumo do Pedido
                Card::make()
                    ->schema([
                        TextInput::make('valor_parcial')
                            ->label('Valor Parcial')
                            ->numeric()
                            ->disabled()
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
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $valorParcial = $get('valor_parcial') ?? 0;
                                $desconto = $state;
                                $valorFinal = $valorParcial - ($valorParcial * $desconto / 100);
                                $set('valor_final', $valorFinal);
                            })
                            ->columnSpan(6),

                        // Campo de desconto em valor fixo
                        TextInput::make('desconto_valor')
                            ->label('Desconto (R$)')
                            ->numeric()
                            ->minValue(0)
                            ->hidden(fn (callable $get) => $get('tipo_desconto') !== 'valor_fixo')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $valorParcial = $get('valor_parcial') ?? 0;
                                $valorFinal = $valorParcial - $state;
                                $set('valor_final', $valorFinal);
                            })
                            ->columnSpan(6),

                        // Valor do desconto aplicado
                        TextInput::make('desconto_valor_calculado')
                            ->label('Valor do Desconto Aplicado')
                            ->numeric()
                            ->disabled()
                            ->columnSpan(6),

                        // Valor final do pedido
                        TextInput::make('valor_final')
                            ->label('Valor Final')
                            ->numeric()
                            ->disabled()
                            ->reactive()
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
                Tables\Columns\TextColumn::make('valor_parcial')->label('Valor Parcial'),
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
}
