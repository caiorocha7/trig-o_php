<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages; // Importa o namespace das páginas associadas ao recurso
use App\Models\Produto;
use Filament\Forms; // Certifique-se de que está usando o namespace correto para Forms
use Filament\Forms\Form; // Importar o Form do Filament\Forms
use Filament\Resources\Resource;
use Filament\Tables; // Certifique-se de que está usando o namespace correto para Tables
use Filament\Tables\Table; // Importar o Table do Filament\Tables

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('codigo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('secao')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('preco')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('quantidade')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('unidade')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')->label('Código'),
                Tables\Columns\TextColumn::make('nome')->label('Nome'),
                Tables\Columns\TextColumn::make('secao')->label('Seção'),
                Tables\Columns\TextColumn::make('preco')->label('Preço'),
                Tables\Columns\TextColumn::make('quantidade')->label('Quantidade'),
                Tables\Columns\TextColumn::make('unidade')->label('Unidade'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}
