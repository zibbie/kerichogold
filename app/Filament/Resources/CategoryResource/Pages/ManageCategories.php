<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function reorderTable(array $order): void
    {
        DB::transaction(function () use ($order) {
            $keyName = (new \App\Models\Category)->getKeyName();
            $cases = collect($order)
                ->map(fn ($recordKey, int $index) => "WHEN {$keyName} = " . DB::getPdo()->quote($recordKey) . " THEN " . ($index + 1))
                ->implode(' ');

            DB::statement("UPDATE categories SET position = CASE {$cases} END WHERE {$keyName} IN (" .
                collect($order)->map(fn ($v) => DB::getPdo()->quote($v))->implode(',') .
            ')');
        });

        \Illuminate\Support\Facades\Cache::forget('global_view_data');
    }
}
