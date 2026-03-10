<?php

namespace App\Filament\Resources;

use App\Filament\Exports\AppealExporter;
use App\Filament\Resources\AppealResource\Pages;
use App\Models\Appeal;
use App\Models\Category;
use App\Services\AppealService;
use App\Services\TelegramService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class AppealResource extends Resource
{
    protected static ?string $model = Appeal::class;

    protected static ?string $navigationIcon  = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Murojaatlar';
    protected static ?string $modelLabel      = 'Murojaat';
    protected static ?string $pluralModelLabel = 'Murojaatlar';
    protected static ?int    $navigationSort  = 1;

    // =========================================================================
    // INFOLIST  (View page — all fields read-only)
    // =========================================================================

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            Infolists\Components\Section::make('Murojaat ma\'lumotlari')
                ->icon('heroicon-o-document-text')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('tracking_code')
                        ->label('Tracking kodi')
                        ->copyable()
                        ->weight('bold')
                        ->color('primary')
                        ->fontFamily('mono'),

                    Infolists\Components\TextEntry::make('status')
                        ->label('Holat')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'reviewing' => 'warning',
                            'responded' => 'success',
                            'rejected'  => 'danger',
                            default     => 'gray',
                        })
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'pending'   => 'Kutilmoqda',
                            'reviewing' => 'Ko\'rib chiqilmoqda',
                            'responded' => 'Javob berildi',
                            'closed'    => 'Yopildi',
                            'rejected'  => 'Rad etildi',
                            default     => $state,
                        }),

                    Infolists\Components\TextEntry::make('full_name')
                        ->label('Ism-familiya'),

                    Infolists\Components\TextEntry::make('phone')
                        ->label('Telefon')
                        ->copyable(),

                    Infolists\Components\TextEntry::make('email')
                        ->label('Elektron pochta')
                        ->copyable()
                        ->placeholder('—'),

                    Infolists\Components\TextEntry::make('category.name_uz')
                        ->label('Kategoriya'),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label('Yuborilgan sana')
                        ->dateTime('d.m.Y H:i'),

                    Infolists\Components\TextEntry::make('subject')
                        ->label('Mavzu')
                        ->columnSpanFull(),

                    Infolists\Components\TextEntry::make('body')
                        ->label('Murojaat matni')
                        ->columnSpanFull()
                        ->prose(),

                    Infolists\Components\TextEntry::make('files')
                        ->label('Ilovalar')
                        ->columnSpanFull()
                        ->html()
                        ->formatStateUsing(function (?array $state): string {
                            if (empty($state)) return '<span class="text-gray-400">—</span>';
                            $links = array_map(fn ($f) =>
                                '<a href="' . Storage::url($f) . '" target="_blank"
                                   class="inline-flex items-center gap-1 text-primary-600 hover:underline text-sm">'
                                . basename($f) . '</a>',
                                $state
                            );
                            return implode('<br>', $links);
                        }),
                ]),

            Infolists\Components\Section::make('Javoblar')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->collapsible()
                ->schema([
                    Infolists\Components\RepeatableEntry::make('responses')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('response_text')
                                ->label('Javob matni')
                                ->prose()
                                ->columnSpanFull(),
                            Infolists\Components\TextEntry::make('respondedBy.name')
                                ->label('Javob berdi')
                                ->placeholder('—'),
                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Sana')
                                ->dateTime('d.m.Y H:i'),
                        ])
                        ->columns(2),
                ]),

            Infolists\Components\Section::make('Holat tarixi')
                ->icon('heroicon-o-clock')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Infolists\Components\RepeatableEntry::make('statuses')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('status')
                                ->label('Holat')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'reviewing' => 'warning',
                                    'responded' => 'success',
                                    'rejected'  => 'danger',
                                    default     => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'pending'   => 'Kutilmoqda',
                                    'reviewing' => 'Ko\'rib chiqilmoqda',
                                    'responded' => 'Javob berildi',
                                    'closed'    => 'Yopildi',
                                    'rejected'  => 'Rad etildi',
                                    default     => $state,
                                }),

                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Sana')
                                ->dateTime('d.m.Y H:i'),

                            Infolists\Components\TextEntry::make('comment')
                                ->label('Izoh')
                                ->placeholder('—')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ]),
        ]);
    }

    // =========================================================================
    // FORM  (used only by modal action forms — no create/edit pages)
    // =========================================================================

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    // =========================================================================
    // TABLE
    // =========================================================================

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('Tracking kodi')
                    ->copyable()
                    ->searchable()
                    ->fontFamily('mono')
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Ism-familiya')
                    ->searchable()
                    ->description(fn (Appeal $r): string => $r->phone),

                Tables\Columns\TextColumn::make('category.name_uz')
                    ->label('Kategoriya')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Holat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'reviewing' => 'warning',
                        'responded' => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Kutilmoqda',
                        'reviewing' => 'Ko\'rib chiqilmoqda',
                        'responded' => 'Javob berildi',
                        'closed'    => 'Yopildi',
                        'rejected'  => 'Rad etildi',
                        default     => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sana')
                    ->date('d.m.Y')
                    ->sortable(),
            ])

            // ── Filters ──────────────────────────────────────────────────────
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Holat')
                    ->options([
                        'pending'   => 'Kutilmoqda',
                        'reviewing' => 'Ko\'rib chiqilmoqda',
                        'responded' => 'Javob berildi',
                        'closed'    => 'Yopildi',
                        'rejected'  => 'Rad etildi',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategoriya')
                    ->options(Category::pluck('name_uz', 'id'))
                    ->searchable()
                    ->multiple(),

                Tables\Filters\Filter::make('created_at')
                    ->label('Sana oralig\'i')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dan')
                            ->native(false)
                            ->displayFormat('d.m.Y'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Gacha')
                            ->native(false)
                            ->displayFormat('d.m.Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'],  fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'])  $indicators[] = Tables\Filters\Indicator::make('Dan: ' . $data['from']);
                        if ($data['until']) $indicators[] = Tables\Filters\Indicator::make('Gacha: ' . $data['until']);
                        return $indicators;
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)

            // ── Row actions ───────────────────────────────────────────────────
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ko\'rish'),

                Tables\Actions\Action::make('changeStatus')
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
                    ->action(function (Appeal $record, array $data): void {
                        app(AppealService::class)->changeStatus(
                            $record,
                            $data['status'],
                            $data['comment'],
                            auth()->id(),
                        );
                        Notification::make()
                            ->title('Holat yangilandi')
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Holat o\'zgartirish')
                    ->modalWidth('lg'),

                Tables\Actions\Action::make('respond')
                    ->label('Javob berish')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->form([
                        Forms\Components\Textarea::make('response_text')
                            ->label('Javob matni')
                            ->placeholder('Foydalanuvchiga javobni yozing...')
                            ->rows(5)
                            ->required()
                            ->minLength(10),
                    ])
                    ->action(function (Appeal $record, array $data): void {
                        app(AppealService::class)->respond(
                            $record,
                            $data['response_text'],
                            auth()->id(),
                        );
                        app(TelegramService::class)->notifyResponseSent($record, $data['response_text']);
                        Notification::make()
                            ->title('Javob yuborildi')
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Javob yuborish')
                    ->modalWidth('lg'),

            ])

            // ── Bulk actions ──────────────────────────────────────────────────
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Excel eksport')
                        ->exporter(AppealExporter::class),
                ]),
            ])

            // ── Header actions ────────────────────────────────────────────────
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->label('Hammasini eksport qilish')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(AppealExporter::class),
            ]);
    }

    // =========================================================================
    // PAGES
    // =========================================================================

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppeals::route('/'),
            'view'  => Pages\ViewAppeal::route('/{record}'),
        ];
    }
}
