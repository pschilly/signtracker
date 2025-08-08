<?php

namespace App\Filament\Resources\Campaigns\Resources\Signs\Pages;

use App\Filament\Resources\Campaigns\Resources\Signs\SignResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSign extends EditRecord
{
    protected static string $resource = SignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
