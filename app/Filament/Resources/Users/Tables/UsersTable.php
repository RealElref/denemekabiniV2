<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->width(60),
                TextColumn::make('name')->label('Ad')->searchable()->weight('bold'),
                TextColumn::make('email')->label('E-posta')->searchable(),
                TextColumn::make('credit_balance')
                    ->label('Kredi')->suffix(' kr')->sortable()
                    ->color(fn ($state) => $state < 3 ? 'danger' : 'success'),
                TextColumn::make('generations_count')
                    ->label('Deneme')->counts('generations')->sortable(),
                IconColumn::make('is_admin')->label('Admin')->boolean(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('created_at')->label('Kayıt')->date('d.m.Y')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
                TernaryFilter::make('is_admin')->label('Admin'),
            ])
            ->actions([
                Action::make('addCredits')
                    ->label('Kredi Ekle')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        TextInput::make('amount')->label('Eklenecek Kredi')->numeric()->required()->minValue(1),
                        Textarea::make('note')->label('Not (opsiyonel)'),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->addCredits((int) $data['amount'], 'gift', $data['note'] ?? null);
                        Notification::make()->title('Kredi eklendi')->success()->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}   