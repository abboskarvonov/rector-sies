<?php

namespace App\Filament\Widgets;

use App\Models\Appeal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppealStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $counts = Appeal::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $labels = [
            Appeal::STATUS_PENDING => 'Kutilmoqda',
            Appeal::STATUS_REVIEWING => "Ko'rib chiqilmoqda",
            Appeal::STATUS_RESPONDED => 'Javob berildi',
            Appeal::STATUS_CLOSED => 'Yopildi',
            Appeal::STATUS_REJECTED => 'Rad etildi',
        ];

        $icons = [
            Appeal::STATUS_PENDING => 'heroicon-o-clock',
            Appeal::STATUS_REVIEWING => 'heroicon-o-magnifying-glass',
            Appeal::STATUS_RESPONDED => 'heroicon-o-chat-bubble-left-right',
            Appeal::STATUS_CLOSED => 'heroicon-o-check-circle',
            Appeal::STATUS_REJECTED => 'heroicon-o-x-circle',
        ];

        $colors = [
            Appeal::STATUS_PENDING => 'gray',
            Appeal::STATUS_REVIEWING => 'warning',
            Appeal::STATUS_RESPONDED => 'success',
            Appeal::STATUS_CLOSED => 'gray',
            Appeal::STATUS_REJECTED => 'danger',
        ];

        return collect(Appeal::STATUSES)
            ->map(fn (string $status) => Stat::make($labels[$status], (string) ($counts[$status] ?? 0))
                ->icon($icons[$status])
                ->color($colors[$status]))
            ->all();
    }
}
