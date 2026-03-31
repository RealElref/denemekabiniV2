<?php

namespace App\Filament\Resources\Domains\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;

class DomainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('domain')
                    ->label('Domain')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pending'  => 'warning',
                        'active'   => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending'  => 'Bekliyor',
                        'active'   => 'Aktif',
                        'rejected' => 'Reddedildi',
                        default    => $state,
                    }),

                TextColumn::make('api_key')
                    ->label('API Key')
                    ->copyable()
                    ->copyMessage('Kopyalandı')
                    ->limit(20)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Talep Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending'  => 'Bekliyor',
                        'active'   => 'Aktif',
                        'rejected' => 'Reddedildi',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status'  => 'active',
                            'api_key' => Str::random(40),
                        ]);
                    })
                    ->visible(fn ($record) => $record->status !== 'active'),

                Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status'  => 'rejected',
                            'api_key' => null,
                        ]);
                    })
                    ->visible(fn ($record) => $record->status !== 'rejected'),

                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
