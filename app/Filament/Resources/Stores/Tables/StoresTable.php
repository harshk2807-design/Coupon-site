<?php

namespace App\Filament\Resources\Stores\Tables;

use App\Models\Store;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/** The stores list in the admin panel. */
class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->disk('public')->circular(),
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('slug')->searchable()->color('gray'),
                TextColumn::make('categories.name')->badge()->limitList(2),
                TextColumn::make('active_coupons_count')
                    ->label('Active coupons')
                    ->counts('activeCoupons')
                    ->sortable(),
                TextColumn::make('rating')->numeric(1)->sortable(),
                IconColumn::make('is_featured')->label('Homepage')->boolean(),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('View page')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Store $record) => $record->pageUrl())
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
