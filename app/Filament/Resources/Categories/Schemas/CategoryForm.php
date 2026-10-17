<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/** The "Add / Edit category" form in the admin panel. */
class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Category')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug(str_replace('&', 'and', (string) $state)));
                            }
                        }),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->regex('/^[a-z0-9-]+$/')
                        ->unique(ignoreRecord: true)
                        ->helperText('Page: /category/your-slug/'),
                    Select::make('parent_id')
                        ->label('Parent category')
                        ->relationship(
                            'parent',
                            'name',
                            // only main categories can be parents (2 levels)
                            fn (Builder $query, $record) => $query->whereNull('parent_id')
                                ->when($record, fn ($q) => $q->whereKeyNot($record->id)),
                        )
                        ->placeholder('— None (this is a main category) —')
                        ->searchable()
                        ->preload(),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers are shown first'),
                ]),
        ]);
    }
}
