<?php

namespace App\Filament\Resources\Campaigns\Resources\Signs\Schemas;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Layout\Split;
use Livewire\Component;

class SignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(12)
                    ->schema([
                        Section::make('Sign Location')
                            ->description('Location where the sign was placed / delivered.')
                            ->afterHeader([
                                Action::make('get_location')
                                    ->icon(Heroicon::MapPin)
                                    ->label('Get Current Location')
                                    ->color('info')
                                    ->action(function (Component $livewire) {
                                        $livewire->js(<<<'JS'
                                            (async () => {
                                                async function fetchAndPopulateLocation() {
                                                    if (!navigator.geolocation) {
                                                        alert("Geolocation is not supported by this browser.");
                                                        return;
                                                    }
                                                    
                                                    try {
                                                        const position = await new Promise((resolve, reject) => {
                                                            navigator.geolocation.getCurrentPosition(resolve, reject, { enableHighAccuracy: true });
                                                        });
                                                        
                                                        const lat = position.coords.lat;
                                                        const long = position.coords.lng;
                                                                                
                                                        $wire.set('data.lat',lat);
                                                        $wire.set('data.lng', long);

                                                        // Get Location by Coords w/ Google Places API
                                                        const googleApiKey = 'AIzaSyC-2wmG0g5n2qIgVwtihiam8p5vDrwuyjw'; 

                                                        const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${long}&key=${googleApiKey}`);
                                                        const data = await response.json();
                                                        
                                                        const address = data.results[0] ? data.results[0].formatted_address : 'Address not found';

                                                        $wire.set('data.address',address);
                                                    } catch (error) {
                                                        console.error("Geolocation error:", error);
                                                        alert("Could not get your location. Please ensure location services are enabled and you have granted permission.");
                                                    }
                                                }

                                                fetchAndPopulateLocation();
                                            })();
                                        JS);
                                    }),
                            ])
                            ->schema([

                                TextInput::make('address')
                                    ->label('Address')
                                    ->columnSpanFull(),
                                Grid::make(6)->schema([
                                    TextInput::make('lat')
                                        ->label('GPS Latitude')
                                        ->columnSpan(3),
                                    TextInput::make('lng')
                                        ->label('GPS Longitude')
                                        ->columnSpan(3)
                                ])
                            ])->columnSpan(6),
                        Section::make('Details')
                            ->description('Who placed it and when? Who recovered it and when?')
                            ->schema([
                                Grid::make(6)->schema([
                                    Select::make('placed_by_user_id')
                                        ->label('Placed By')
                                        ->relationship('placedByUser', 'name')
                                        ->preload()
                                        ->searchable()
                                        ->default(auth()->user()->id)
                                        ->columnSpan(3),
                                    DateTimePicker::make('placed_at')->default(Carbon::now())
                                        ->columnSpan(3),
                                ]),
                                Grid::make(6)->schema([
                                    Select::make('recovered_by_user_id')
                                        ->label('Recovered By')
                                        ->relationship('recoveredByUser', 'name')
                                        ->preload()
                                        ->searchable()->columnSpan(3),
                                    DateTimePicker::make('recovered_at')->columnSpan(3),
                                ])
                            ])->columnSpan(6),
                    ])
                    ->columnSpanFull(),


                Section::make('Notes & Image')
                    ->description('Snap a photo of the sign location & provide any other pertinent information.')
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->directory(fn() => Filament::getTenant()->domain . '/sign-images')
                            ->columnSpan(6),

                        RichEditor::make('notes')
                            ->columnSpan(6)
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'link',],
                                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                                ['blockquote', 'bulletList', 'orderedList'],
                                ['undo', 'redo'],
                            ]),
                    ])
            ]);
    }
}
