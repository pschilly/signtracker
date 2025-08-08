<?php

namespace App\Filament\Resources\Campaigns\Widgets;

use App\Models\Campaign;
use App\Models\Sign;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class MapWidget extends Widget
{
    protected string $view = 'filament.resources.campaigns.widgets.map-widget';

    public ?Campaign $campaign;

    protected static bool $isLazy = false;

    protected array|string|int $columnSpan = 'full';

    public $signs;

    public function mount(): void
    {
        $userTimezone = auth()->user()->timezone ?? 'America/Toronto';

        $this->signs = $this->campaign
            ->signs()
            ->with(['placedByUser', 'recoveredByUser'])
            ->get(['lat', 'lng', 'address', 'placed_at', 'recovered_at', 'placed_by_user_id', 'recovered_by_user_id'])
            ->map(function ($sign) use ($userTimezone) {
                // Build a new, clean array to bypass Eloquent's serialization.
                return [
                    'lat' => (float) $sign->lat, // Cast to float here for good measure
                    'lng' => (float) $sign->lng,
                    'address' => $sign->address,
                    // Format the date string explicitly.
                    'placed_at' => (!is_null($sign->placed_at)) ? Carbon::parse($sign->placed_at)->setTimezone($userTimezone)->format('d M y') : 'Has not been placed.',
                    'recovered_at' => (!is_null($sign->recovered_at)) ? Carbon::parse($sign->recovered_at)->setTimezone($userTimezone)->format('d M y') : 'Has not been recovered.',
                    'placed_by' => $sign->placedByUser->name ?? 'N/A',
                    'recovered_by' => $sign->recoveredByUser->name ?? 'N/A',
                ];
            });
    }

    public function getMapApiKeyProperty()
    {
        // Store your Google Maps API key in your .env file
        return env('GOOGLE_PLACES_API_KEY');
    }

    public function getMapIdProperty()
    {
        // Store your Map ID in your .env file
        return env('GOOGLE_MAP_ID');
    }
}
