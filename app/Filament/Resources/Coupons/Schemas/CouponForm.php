<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/** The "Add / Edit coupon" form in the admin panel. */
class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Coupon')
                ->columns(2)
                ->schema([
                    Select::make('store_id')
                        ->label('Store')
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    ToggleButtons::make('type')
                        ->options(['code' => 'Coupon code', 'deal' => 'Deal (no code)'])
                        ->default('code')
                        ->inline()
                        ->required()
                        ->live(),
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->placeholder('Get 30% off your first order'),
                    Textarea::make('description')
                        ->rows(2)
                        ->columnSpanFull(),
                    TextInput::make('code')
                        ->placeholder('SAVE30')
                        ->visible(fn (Get $get) => $get('type') === 'code')
                        ->required(fn (Get $get) => $get('type') === 'code'),
                    TextInput::make('discount')
                        ->label('Discount label')
                        ->placeholder('30% OFF'),
                    TextInput::make('url')
                        ->label('Deal URL')
                        ->url()
                        ->helperText('Leave empty to use the store URL')
                        ->columnSpanFull(),
                    DatePicker::make('expires_at')
                        ->label('Expiry date')
                        ->helperText('Hidden on the website after this day. Leave empty = never expires'),
                    Toggle::make('verified')
                        ->inline(false),
                ]),
        ]);
    }
}
