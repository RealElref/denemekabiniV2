<?php

namespace App\Filament\Resources\Packages;

use App\Filament\Resources\Packages\Schemas\PackageForm;
use App\Filament\Resources\Packages\Tables\PackagesTable;
use App\Models\Package;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel  = 'Paketler';
    protected static ?string $modelLabel       = 'Paket';
    protected static ?string $pluralModelLabel = 'Paketler';

    public static function form(Schema $schema): Schema
    {
        return PackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Resources\Packages\Pages\ListPackages::route('/'),
            'create' => \App\Filament\Resources\Packages\Pages\CreatePackage::route('/create'),
            'edit'   => \App\Filament\Resources\Packages\Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}