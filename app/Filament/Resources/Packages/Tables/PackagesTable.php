<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable()->width(50),
                TextColumn::make('name')->label('Paket')->searchable()->weight('bold'),
                TextColumn::make('credit_amount')->label('Kredi')->suffix(' kredi')->sortable(),
                TextColumn::make('price')->label('Fiyat')
                    ->formatStateUsing(fn ($state) => number_format($state / 100, 2) . ' ₺')->sortable(),
                TextColumn::make('badge_label')->label('Rozet'),
                IconColumn::make('is_featured')->label('Öne Çıkan')->boolean(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('sort_order');
    }
}