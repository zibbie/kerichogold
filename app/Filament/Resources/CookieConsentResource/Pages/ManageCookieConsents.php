<?php

namespace App\Filament\Resources\CookieConsentResource\Pages;

use App\Filament\Resources\CookieConsentResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCookieConsents extends ManageRecords
{
    protected static string $resource = CookieConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
