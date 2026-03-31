<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([

            // Sol kolon
            Section::make('Temel Bilgiler')
                ->columnSpan(1)
                ->schema([
                    TextInput::make('name')
                        ->label('Paket Adı')
                        ->required()
                        ->maxLength(100)
                        ->columnSpanFull(),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100)
                        ->columnSpanFull(),
                    TextInput::make('credit_amount')
                        ->label('Kredi Miktarı')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->suffix('kredi')
                        ->columnSpanFull(),
                    TextInput::make('price')
                        ->label('Fiyat')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->suffix('₺')
                        ->helperText('Kuruş cinsinden: 9900 = 99,00 ₺')
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label('Açıklama')
                        ->rows(3)
                        ->columnSpanFull(),
                    Repeater::make('features')
                        ->label('Özellikler')
                        ->schema([
                            TextInput::make('item')->label('Özellik')->required(),
                        ])
                        ->defaultItems(0)
                        ->addActionLabel('+ Özellik Ekle')
                        ->columnSpanFull(),
                ]),

            // Sağ kolon
            Section::make('Ayarlar')
                ->columnSpan(1)
                ->schema([
                    TextInput::make('sort_order')
                        ->label('Sıra')
                        ->numeric()
                        ->default(0),
                    TextInput::make('badge_label')
                        ->label('Rozet Yazısı')
                        ->placeholder('En Popüler'),
                    Select::make('badge_color')
                        ->label('Rozet Rengi')
                        ->options([
                            'primary' => 'Mor',
                            'success' => 'Yeşil',
                            'warning' => 'Turuncu',
                            'danger'  => 'Kırmızı',
                            'info'    => 'Mavi',
                        ]),
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                    Toggle::make('is_featured')
                        ->label('Öne Çıkar'),
                ]),
        ]);
    }
}