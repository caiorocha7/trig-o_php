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
                                            $set('preco', $produto->preco);
                                        } else {
                                            $set('preco', null);
                                        }
                                    })
                                    ->columnSpan(4),

                                TextInput::make('preco')
                                    ->label('Preço Unitário')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(4),

                                TextInput::make('quantidade')
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $preco = $get('preco') ?? 0;
                                        $set('subtotal', $preco * $state);
                                    })
                                    ->columnSpan(4),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(4),
                            ])
                            ->columns(12)
                            ->label('Produtos do Pedido')
                            ->columnSpan(12)
                            ->afterStateHydrated(function (callable $get, callable $set) {
                                $valorFinal = collect($get('produtos') ?? [])->sum(fn ($produto) => $produto['subtotal'] ?? 0);
                                $set('valor_final', $valorFinal);
                            })
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $valorFinal = collect($get('produtos') ?? [])->sum(fn ($produto) => $produto['subtotal'] ?? 0);
                                $set('valor_final', $valorFinal);
                            }),
                    ])
                    ->columns(12),

                // Card de Resumo do Pedido
                Card::make()
                    ->schema([
                        TextInput::make('valor_final')
                            ->label('Valor Total')
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
                Tables\Columns\TextColumn::make('valor_final')->label('Valor Total'),
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
