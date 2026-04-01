<?php

namespace App\Filament\Resources\Domains\Pages;

use App\Filament\Resources\Domains\DomainResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDomain extends EditRecord
{
    protected static string $resource = DomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $domain = $this->record;

        if ($domain->status === 'active' && is_null($domain->registered_at)) {
            $registeredAt = now();
            $domain->registered_at = $registeredAt;
            $domain->expires_at    = $registeredAt->copy()->addYears($domain->registration_years ?? 1);
            $domain->save();
        }
    }
}
