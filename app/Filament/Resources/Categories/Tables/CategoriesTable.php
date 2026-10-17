<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/** The categories list in the admin panel. */
class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('parent.name')
                    ->label('Main category')
                    ->placeholder('— (main category)')
                    ->sortable(),
                TextColumn::make('slug')->color('gray'),
                TextColumn::make('stores_count')->label('Stores')->counts('stores')->sortable(),
                TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->defaultSort('parent_id')
            ->filters([
                TernaryFilter::make('main')
                    ->label('Level')
                    ->trueLabel('Main categories')
                    ->falseLabel('Subcategories')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNull('parent_id'),
                        false: fn (Builder $query) => $query->whereNotNull('parent_id'),
                    ),
                SelectFilter::make('parent')
                    ->label('Main category')
                    ->relationship('parent', 'name', fn (Builder $query) => $query->whereNull('parent_id')),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('View page')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Category $record) => $record->url())
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}