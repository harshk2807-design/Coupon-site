<?php

namespace App\Filament\Resources\Coupons\Tables;

use App\Models\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/** The coupons list in the admin panel. */
class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store.name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('title')->searchable()->limit(50),
                TextColumn::make('code')
                    ->getStateUsing(fn (Coupon $record) => $record->isCode() ? $record->code : 'DEAL')
                    ->badge()
                    ->color(fn (Coupon $record) => $record->isCode() ? 'primary' : 'gray'),
                TextColumn::make('discount'),
                IconColumn::make('verified')->boolean(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->date()
                    ->sortable()
                    ->placeholder('Never')
                    ->color(fn (Coupon $record) => $record->isExpired() ? 'danger' : null)
                    ->description(fn (Coupon $record) => $record->isExpired() ? 'Expired (hidden)' : null),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('store')->relationship('store', 'name')->searchable()->preload(),
                TernaryFilter::make('active')
                    ->label('Status')
                    ->trueLabel('Active only')
                    ->falseLabel('Expired only')
                    ->queries(
                        true: fn (Builder $query) => $query->active(),
                        false: fn (Builder $query) => $query->whereDate('expires_at', '<', today()),
                    ),
                TernaryFilter::make('verified'),
            ])
            ->recordActions([
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