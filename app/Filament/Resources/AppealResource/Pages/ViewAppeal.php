<?php

namespace App\Filament\Resources\AppealResource\Pages;

use App\Filament\Resources\AppealResource;
use App\Services\AppealService;
use App\Services\TelegramService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewAppeal extends ViewRecord
{
    protected static string $resource = AppealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('changeStatus')
                ->label('Holat o\'zgartirish')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label('Yangi holat')
                        ->options([
                            'reviewing' => 'Ko\'rib chiqilmoqda',
                            'responded' => 'Javob berildi',
                            'closed'    => 'Yopildi',
                            'rejected'  => 'Rad etildi',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('comment')
                        ->label('Izoh')
                        ->placeholder('Holat o\'zgarishini izohlang...')
                        ->rows(3)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    app(AppealService::class)->changeStatus(
                        $this->record,
                        $data['status'],
                        $data['comment'],
                        auth()->id(),
                    );
                    $this->refreshFormData(['status']);
                    Notification::make()
                        ->title('Holat yangilandi')
                        ->success()
                        ->send();
                })
                ->modalHeading('Holat o\'zgartirish')
                ->modalWidth('lg'),

            Actions\Action::make('respond')
                ->label('Javob berish')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->form([
                    Forms\Components\Textarea::make('response_text')
                        ->label('Javob matni')
                        ->placeholder('Foydalanuvchiga javobni yozing...')
                        ->rows(6)
                        ->required()
                        ->minLength(10),
                ])
                ->action(function (array $data): void {
                    app(AppealService::class)->respond(
                        $this->record,
                        $data['response_text'],
                        auth()->id(),
                    );
                    app(TelegramService::class)->notifyResponseSent(
                        $this->record,
                        $data['response_text'],
                    );
                    $this->refreshFormData(['status']);
                    Notification::make()
                        ->title('Javob muvaffaqiyatli yuborildi')
                        ->success()
                        ->send();
                })
                ->modalHeading('Javob yuborish')
                ->modalWidth('lg'),

            Actions\Action::make('back')
                ->label('Ro\'yxatga qaytish')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(AppealResource::getUrl('index')),
        ];
    }
}
