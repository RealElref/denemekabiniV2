<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->label('Kullanıcı')
                ->relationship('user', 'name')->searchable()->required(),
            Select::make('package_id')->label('Paket')
                ->relationship('package', 'name')->searchable(),
            Select::make('type')->label('İşlem Tipi')
                ->options([
                    'purchase' => 'Satın Alma',
                    'gift'     => 'Hediye',
                    'referral' => 'Referans',
                    'bonus'    => 'Bonus',
                    'refund'   => 'İade',
                ])->required()->default('gift'),
            TextInput::make('amount')->label('Tutar (Kuruş)')
                ->numeric()->required()->default(0)
                ->helperText('Örnek: 9900 = 99,00 ₺. Hediye için 0 girin.'),
            TextInput::make('credit_amount')->label('Kredi Miktarı')
                ->numeric()->required()->minValue(1),
            Select::make('status')->label('Durum')
                ->options([
                    'pending'   => 'Bekliyor',
                    'paid'      => 'Ödendi',
                    'failed'    => 'Başarısız',
                    'refunded'  => 'İade',
                    'cancelled' => 'İptal',
                ])->required()->default('paid'),
            Textarea::make('note')->label('Not'),
        ]);
    }
}