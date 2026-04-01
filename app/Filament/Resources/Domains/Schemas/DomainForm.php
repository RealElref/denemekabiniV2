<?php

namespace App\Filament\Resources\Domains\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([

            Section::make('Domain Bilgileri')
                ->columnSpan(1)
                ->schema([
                    Select::make('user_id')
                        ->label('Kullanıcı')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),
                    TextInput::make('domain_name')
                        ->label('Domain Adı')
                        ->placeholder('orneksite')
                        ->required(),
                    Select::make('tld')
                        ->label('Uzantı')
                        ->options([
                            '.com'    => '.com',
                            '.net'    => '.net',
                            '.org'    => '.org',
                            '.com.tr' => '.com.tr',
                            '.net.tr' => '.net.tr',
                            '.org.tr' => '.org.tr',
                            '.tr'     => '.tr',
                            '.io'     => '.io',
                            '.co'     => '.co',
                        ])
                        ->required(),
                    Select::make('registration_years')
                        ->label('Kayıt Süresi')
                        ->options([
                            1 => '1 Yıl',
                            2 => '2 Yıl',
                            3 => '3 Yıl',
                            5 => '5 Yıl',
                        ])
                        ->default(1)
                        ->required(),
                    TextInput::make('credits_used')
                        ->label('Kullanılan Kredi')
                        ->numeric()
                        ->required()
                        ->default(1),
                    TextInput::make('price_paid')
                        ->label('Ödenen Tutar (Kuruş)')
                        ->numeric()
                        ->helperText('Örnek: 9900 = 99,00 ₺'),
                ]),

            Section::make('Durum & Notlar')
                ->columnSpan(1)
                ->schema([
                    Select::make('status')
                        ->label('Durum')
                        ->options([
                            'pending'   => 'Onay Bekliyor',
                            'approved'  => 'Onaylandı',
                            'active'    => 'Aktif',
                            'rejected'  => 'Reddedildi',
                            'expired'   => 'Süresi Doldu',
                            'cancelled' => 'İptal',
                        ])
                        ->required()
                        ->default('pending'),
                    Textarea::make('admin_note')
                        ->label('Admin Notu')
                        ->rows(3),
                    DateTimePicker::make('registered_at')
                        ->label('Kayıt Tarihi')
                        ->helperText('Boş bırakılırsa durum "Aktif" yapıldığında otomatik ayarlanır.'),
                    DateTimePicker::make('expires_at')
                        ->label('Bitiş Tarihi')
                        ->helperText('Boş bırakılırsa kayıt tarihine + kayıt yılı kadar otomatik hesaplanır.'),
                ]),
        ]);
    }
}