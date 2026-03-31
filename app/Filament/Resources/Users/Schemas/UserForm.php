<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kişisel Bilgiler')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('Ad Soyad')->required(),
                    TextInput::make('email')->label('E-posta')->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('phone')->label('Telefon'),
                    TextInput::make('password')->label('Şifre')->password()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context) => $context === 'create'),
                ]),
            Section::make('Kredi & Durum')
                ->columns(3)
                ->schema([
                    TextInput::make('credit_balance')->label('Kredi Bakiyesi')->numeric()->required()->minValue(0),
                    Toggle::make('is_active')->label('Aktif')->default(true),
                    Toggle::make('is_admin')->label('Admin'),
                ]),
        ]);
    }
}