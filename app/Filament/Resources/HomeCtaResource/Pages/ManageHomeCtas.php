<?php

namespace App\Filament\Resources\HomeCtaResource\Pages;

use App\Filament\Resources\HomeCtaResource;
use Filament\Resources\Pages\ManageRecords;

class ManageHomeCtas extends ManageRecords
{
    protected static string $resource = HomeCtaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
