<?php

namespace App\Filament\Exports;

use App\Models\Appeal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AppealExporter extends Exporter
{
    protected static ?string $model = Appeal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('tracking_code')
                ->label('Tracking kodi'),

            ExportColumn::make('full_name')
                ->label('Ism-familiya'),

            ExportColumn::make('phone')
                ->label('Telefon'),

            ExportColumn::make('email')
                ->label('Elektron pochta'),

            ExportColumn::make('category.name_uz')
                ->label('Kategoriya'),

            ExportColumn::make('subject')
                ->label('Mavzu'),

            ExportColumn::make('body')
                ->label('Murojaat matni'),

            ExportColumn::make('status')
                ->label('Holat')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending'   => 'Kutilmoqda',
                    'reviewing' => 'Ko\'rib chiqilmoqda',
                    'responded' => 'Javob berildi',
                    'closed'    => 'Yopildi',
                    'rejected'  => 'Rad etildi',
                    default     => $state,
                }),

            ExportColumn::make('ip_address')
                ->label('IP manzil'),

            ExportColumn::make('created_at')
                ->label('Yuborilgan sana'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $rows = number_format($export->successful_rows);
        $body = "Eksport yakunlandi. {$rows} ta murojaat eksport qilindi.";

        if ($failed = $export->getFailedRowsCount()) {
            $body .= " {$failed} ta qator eksport qilinmadi.";
        }

        return $body;
    }
}
