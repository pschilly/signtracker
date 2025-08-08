<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Filament\Resources\Campaigns\Resources\Signs\SignResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManageCampaignSigns extends ManageRelatedRecords
{
    protected static string $resource = CampaignResource::class;

    protected static string $relationship = 'signs';

    protected static ?string $relatedResource = SignResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->color('info')
                    ->icon(Heroicon::Flag)
                    ->label('Add a Sign'),
            ]);
    }
}
