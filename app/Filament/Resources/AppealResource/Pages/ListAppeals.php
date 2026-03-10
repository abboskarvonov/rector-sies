<?php

namespace App\Filament\Resources\AppealResource\Pages;

use App\Filament\Exports\AppealExporter;
use App\Filament\Resources\AppealResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppeals extends ListRecords
{
    protected static string $resource = AppealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Eksport')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(AppealExporter::class),
        ];
    }
}
