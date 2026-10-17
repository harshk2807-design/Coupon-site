<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Models\Category;
use App\Support\Site;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/** The "Add / Edit store" form in the admin panel. */
class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Store details')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        // typing "Temu" fills slug "temu" automatically (only when creating)
                        ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug((string) $state));
                            }
                        }),
                    TextInput::make('slug')
                        ->label('Slug (becomes the subdomain)')
                        ->required()
                        ->maxLength(63)
                        ->regex('/^[a-z0-9-]+$/')
                        ->unique(ignoreRecord: true)
                        ->live(onBlur: true)
                        ->helperText(fn (Get $get) => 'Store page: ' . ($get('slug') ? Site::storeUrl($get('slug')) : '—')),
                    TextInput::make('url')
                        ->label('Store / affiliate URL')
                        ->url()
                        ->placeholder('https://www.temu.com/?ref=your-id'),
                    TextInput::make('button_text')
                        ->placeholder('Activate 10% Cash Back'),
                    TextInput::make('rating')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(5)
                        ->step(0.1)
                        ->default(0),
                    Toggle::make('is_featured')
                        ->label('Show on homepage')
                        ->inline(false),
                ]),

            Section::make('Logo & categories')
                ->columns(2)
                ->schema([
                    FileUpload::make('logo')
                        ->image()
                        ->disk('public')
                        ->directory('logos')
                        ->maxSize(2048)
                        ->helperText('Square image, max 2 MB'),
                    Select::make('categories')
                        ->relationship('categories', 'name')
                        ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->parent
                            ? $record->parent->name . ' › ' . $record->name
                            : $record->name)
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->helperText('Usually pick subcategories, e.g. "Lifestyle › Fashion & Apparel"'),
                ]),

            Section::make('About / FAQ')
                ->schema([
                    Textarea::make('about')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
