<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Models\Produto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                // Tables\Columns\TextColumn::make('preco')->label('Preço'),
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

    // Adiciona um botão global para criar novo produto no canto superior direito
    public static function getGlobalActions(): array
    {
        return [
            Tables\Actions\Action::make('create')
                ->label('Adicionar Novo Produto')
                ->icon('heroicon-o-plus')
                ->url(static::getUrl('create'))
                ->button(),
        ];
    }
}
