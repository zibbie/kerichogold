<?php

namespace App\Filament\Resources\HeroBannerResource\Pages;

use App\Filament\Resources\HeroBannerResource;
use Filament\Resources\Pages\ManageRecords;

class ManageHeroBanners extends ManageRecords
{
    protected static string $resource = HeroBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
