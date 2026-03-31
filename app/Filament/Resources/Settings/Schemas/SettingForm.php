<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')->label('Anahtar')->disabled(),
            TextInput::make('label')->label('Başlık')->disabled(),
            TextInput::make('group')->label('Grup')->disabled(),
            Textarea::make('value')->label('Değer')->rows(3),
        ]);
    }
}