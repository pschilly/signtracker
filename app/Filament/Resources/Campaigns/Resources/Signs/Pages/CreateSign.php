<?php

namespace App\Filament\Resources\Campaigns\Resources\Signs\Pages;

use App\Filament\Resources\Campaigns\Resources\Signs\SignResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Component;

class CreateSign extends CreateRecord
{
    protected static string $resource = SignResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
