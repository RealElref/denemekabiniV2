<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')->label('Grup')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match($state) {
                        'general' => 'Genel',
                        'seo'     => 'SEO',
                        'payment' => 'Ödeme',
                        'api'     => 'API',
                        'social'  => 'Sosyal',
                        default   => $state,
                    }),
                TextColumn::make('label')->label('Ayar')->searchable(),
                TextColumn::make('value')->label('Değer')->limit(50)->searchable(),
                IconColumn::make('is_public')->label('Herkese Açık')->boolean(),
            ])
            ->filters([
                SelectFilter::make('group')->label('Grup')
                    ->options([
                        'general' => 'Genel',
                        'seo'     => 'SEO',
                        'payment' => 'Ödeme',
                        'api'     => 'API',
                        'social'  => 'Sosyal',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('group');
    }
}