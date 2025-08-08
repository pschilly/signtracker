<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                TextInput::make('domain')
                    ->unique()
                    ->prefixIcon(Heroicon::GlobeAlt)
                    ->suffix('.sign-tracker.app')
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $data['owner_id'] = auth()->user()->id;

        $team = Team::create($data);

        $team->users()->attach(auth()->user());

        return $team;
    }
}
