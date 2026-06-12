<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WishlistItemResource\Pages;
use App\Models\Customer;
use App\Models\Product;
use App\Models\WishlistItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WishlistItemResource extends Resource
{
    protected static ?string $model = WishlistItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.customers_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.wishlist');
    }

    public static function getModelLabel(): string
    {
        return __('admin.wishlist_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.wishlist');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label(__('admin.customer'))
                    ->options(fn () => Customer::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('product_id')
                    ->label(__('admin.product'))
                    ->options(function () {
                        return Product::query()
                            ->with('arabicTranslation')
                            ->orderByDesc('created_at')
                            ->get()
                            ->mapWithKeys(fn ($product) => [
                                $product->id => $product->arabicTranslation?->name ?? 'Product #' . $product->id,
                            ])
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with([
                'customer',
                'product.arabicTranslation',
                'product.englishTranslation',
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('admin.customer'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.phone')
                    ->label(__('admin.phone'))
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),

                Tables\Columns\ImageColumn::make('product.main_image')
                    ->label(__('admin.image'))
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('product.arabicTranslation.name')
                    ->label(__('admin.product'))
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('product.price')
                    ->label(__('admin.price'))
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', ',') . ' EGP')
                    ->sortable(),

                Tables\Columns\IconColumn::make('product.is_active')
                    ->label(__('admin.active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.added_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label(__('admin.customer'))
                    ->options(fn () => Customer::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray()),

                Tables\Filters\SelectFilter::make('product_id')
                    ->label(__('admin.product'))
                    ->options(function () {
                        return Product::query()
                            ->with('arabicTranslation')
                            ->get()
                            ->mapWithKeys(fn ($product) => [
                                $product->id => $product->arabicTranslation?->name ?? 'Product #' . $product->id,
                            ])
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('admin.wishlist_item_details'))
                    ->schema([
                        Infolists\Components\TextEntry::make('customer.name')
                            ->label(__('admin.customer')),

                        Infolists\Components\TextEntry::make('customer.phone')
                            ->label(__('admin.phone'))
                            ->copyable()
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('customer.email')
                            ->label(__('admin.email'))
                            ->copyable()
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('product.arabicTranslation.name')
                            ->label(__('admin.product')),

                        Infolists\Components\ImageEntry::make('product.main_image')
                            ->label(__('admin.image'))
                            ->disk('public'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('admin.added_at'))
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWishlistItems::route('/'),
            'create' => Pages\CreateWishlistItem::route('/create'),
            'view' => Pages\ViewWishlistItem::route('/{record}'),
            'edit' => Pages\EditWishlistItem::route('/{record}/edit'),
        ];
    }
}