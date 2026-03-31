<?php

namespace App\Filament\Resources\Domains;

use App\Filament\Resources\Domains\Schemas\DomainForm;
use App\Filament\Resources\Domains\Tables\DomainsTable;
use App\Models\Domain;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel  = 'Domain Talepleri';
    protected static ?string $modelLabel       = 'Domain';
    protected static ?string $pluralModelLabel = 'Domainler';

    public static function getNavigationBadge(): ?string
    {
        $count = Domain::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return DomainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DomainsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Domains\Pages\ListDomains::route('/'),
            'edit'  => \App\Filament\Resources\Domains\Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
