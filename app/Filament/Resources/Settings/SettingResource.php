<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Schemas\SettingForm;
use App\Filament\Resources\Settings\Tables\SettingsTable;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel  = 'Site Ayarları';
    protected static ?string $modelLabel       = 'Ayar';
    protected static ?string $pluralModelLabel = 'Ayarlar';

    public static function form(Schema $schema): Schema
    {
        return SettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Settings\Pages\ListSettings::route('/'),
            'edit'  => \App\Filament\Resources\Settings\Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}