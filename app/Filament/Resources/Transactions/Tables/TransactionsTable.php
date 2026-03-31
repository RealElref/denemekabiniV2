<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')->label('Sipariş No')->searchable(),
                TextColumn::make('user.name')->label('Kullanıcı')->searchable(),
                TextColumn::make('package.name')->label('Paket'),
                TextColumn::make('amount')->label('Tutar')
                    ->formatStateUsing(fn ($state) => number_format($state / 100, 2) . ' ₺'),
                TextColumn::make('credit_amount')->label('Kredi')->suffix(' kr'),
                TextColumn::make('status')->label('Durum')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'pending'   => 'warning',
                        'paid'      => 'success',
                        'failed'    => 'danger',
                        'refunded'  => 'info',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match($state) {
                        'pending'   => 'Bekliyor',
                        'paid'      => 'Ödendi',
                        'failed'    => 'Başarısız',
                        'refunded'  => 'İade',
                        'cancelled' => 'İptal',
                        default     => $state,
                    }),
                TextColumn::make('paid_at')->label('Ödeme Tarihi')->dateTime('d.m.Y H:i'),
                TextColumn::make('created_at')->label('Tarih')->date('d.m.Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')
                    ->options([
                        'pending' => 'Bekliyor',
                        'paid'    => 'Ödendi',
                        'failed'  => 'Başarısız',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}