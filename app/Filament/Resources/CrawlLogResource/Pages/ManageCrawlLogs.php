<?php

namespace App\Filament\Resources\CrawlLogResource\Pages;

use App\Filament\Resources\CrawlLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCrawlLogs extends ManageRecords
{
    protected static string $resource = CrawlLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
