<?php

namespace App\Filament\Resources\Campaigns\Resources\Signs;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Filament\Resources\Campaigns\Resources\Signs\Pages\CreateSign;
use App\Filament\Resources\Campaigns\Resources\Signs\Pages\EditSign;
use App\Filament\Resources\Campaigns\Resources\Signs\Schemas\SignForm;
use App\Filament\Resources\Campaigns\Resources\Signs\Tables\SignsTable;
use App\Models\Sign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SignResource extends Resource
{
    protected static ?string $model = Sign::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Flag;

    protected static ?string $parentResource = CampaignResource::class;

    public static function form(Schema $schema): Schema
    {
        return SignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SignsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateSign::route('/create'),
            'edit' => EditSign::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
