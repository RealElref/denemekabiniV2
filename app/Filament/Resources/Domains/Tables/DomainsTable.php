<?php

namespace App\Filament\Resources\Domains\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;

class DomainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_domain')
                    ->label('Domain')
                    ->getStateUsing(fn ($record) => $record->domain_name . $record->tld)
                    ->searchable(query: fn ($query, $search) => $query
                        ->where('domain_name', 'like', "%{$search}%")
                    )
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('E-posta')
                    ->searchable()
                    ->toggleable()
                    ->color('gray'),

                TextColumn::make('registration_years')
                    ->label('Süre')
                    ->formatStateUsing(fn ($state) => $state . ' yıl')
                    ->alignCenter(),

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pending'   => 'warning',
                        'approved'  => 'info',
                        'active'    => 'success',
                        'rejected'  => 'danger',
                        'expired'   => 'gray',
                        'cancelled' => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending'   => 'Bekliyor',
                        'approved'  => 'Onaylandı',
                        'active'    => 'Aktif',
                        'rejected'  => 'Reddedildi',
                        'expired'   => 'Süresi Doldu',
                        'cancelled' => 'İptal',
                        default     => $state,
                    }),

                TextColumn::make('admin_note')
                    ->label('Admin Notu')
                    ->limit(40)
                    ->toggleable()
                    ->color('gray'),

                TextColumn::make('api_key')
                    ->label('API Key')
                    ->copyable()
                    ->copyMessage('Kopyalandı')
                    ->limit(16)
                    ->toggleable()
                    ->fontFamily('mono'),

                TextColumn::make('created_at')
                    ->label('Talep Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending'   => 'Bekliyor',
                        'approved'  => 'Onaylandı',
                        'active'    => 'Aktif',
                        'rejected'  => 'Reddedildi',
                        'expired'   => 'Süresi Doldu',
                        'cancelled' => 'İptal',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Domain Talebini Onayla')
                    ->modalDescription(fn ($record) => $record->domain_name . $record->tld . ' adresini onaylamak istediğinizden emin misiniz?')
                    ->action(function ($record) {
                        $record->update([
                            'status'  => 'active',
                            'api_key' => Str::random(40),
                        ]);
                    })
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'approved', 'rejected'])),

                Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('admin_note')
                            ->label('Red Sebebi (Opsiyonel)')
                            ->placeholder('Kullanıcıya gösterilecek red sebebini yazın...')
                            ->rows(3),
                    ])
                    ->modalHeading('Domain Talebini Reddet')
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'     => 'rejected',
                            'api_key'    => null,
                            'admin_note' => $data['admin_note'] ?? null,
                        ]);
                    })
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'approved', 'active'])),

                EditAction::make()->label('Düzenle'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
