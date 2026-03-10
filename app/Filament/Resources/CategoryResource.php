<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon  = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategoriyalar';
    protected static ?string $modelLabel      = 'Kategoriya';
    protected static ?string $pluralModelLabel = 'Kategoriyalar';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([

                Forms\Components\TextInput::make('name_uz')
                    ->label('Nomi')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Select::make('icon')
                    ->label('Icon')
                    ->options([
                        '📋' => '📋  Umumiy murojaat',
                        '📚' => '📚  Ta\'lim va o\'quv jarayoni',
                        '🎓' => '🎓  Ilmiy faoliyat',
                        '👥' => '👥  Xodimlar va kadrlar',
                        '💰' => '💰  Moliya va iqtisod',
                        '🏗️' => '🏗️  Qurilish va infrastruktura',
                        '🏠' => '🏠  Yotoqxona va turar-joy',
                        '🚗' => '🚗  Transport',
                        '💻' => '💻  Axborot texnologiyalari',
                        '⚖️' => '⚖️  Huquqiy masalalar',
                        '🌍' => '🌍  Xalqaro aloqalar',
                        '🎭' => '🎭  Madaniyat va sport',
                        '📝' => '📝  Hujjatlar va buyruqlar',
                        '🤝' => '🤝  Hamkorlik',
                        '❓' => '❓  Boshqa',
                    ])
                    ->native(false)
                    ->searchable()
                    ->placeholder('Icon tanlang'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Faol')
                    ->default(true)
                    ->inline(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->width(40),

                Tables\Columns\TextColumn::make('name_uz')
                    ->label('Nomi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Faol')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('appeals_count')
                    ->label('Murojaatlar')
                    ->counts('appeals')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Holat')
                    ->trueLabel('Faol')
                    ->falseLabel('Nofaol'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Tahrirlash'),
                Tables\Actions\DeleteAction::make()->label('O\'chirish'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('O\'chirish'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
