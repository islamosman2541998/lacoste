<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use App\Services\CouponService;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('admin.payments');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.payment_information'))
                    ->schema([
                        Forms\Components\Select::make('method')
                            ->label(__('admin.payment_method'))
                            ->options([
                                'cash_on_delivery' => __('admin.cash_on_delivery'),
                                'bank_transfer' => __('admin.bank_transfer'),
                                'wallet_transfer' => __('admin.wallet_transfer'),
                                'manual' => __('admin.manual_payment'),
                            ])
                            ->required()
                            ->default(fn() => $this->ownerRecord->payment_method ?? 'cash_on_delivery'),

                        Forms\Components\Select::make('status')
                            ->label(__('admin.payment_status'))
                            ->options([
                                'pending' => __('admin.payment_pending'),
                                'paid' => __('admin.payment_paid'),
                                'failed' => __('admin.payment_failed'),
                                'refunded' => __('admin.payment_refunded'),
                            ])
                            ->required()
                            ->default('pending')
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state === 'paid') {
                                    $set('paid_at', now());
                                }
                            }),

                        Forms\Components\TextInput::make('amount')
                            ->label(__('admin.amount'))
                            ->numeric()
                            ->required()
                            ->default(fn() => $this->ownerRecord->grand_total)
                            ->prefix('EGP'),

                        Forms\Components\TextInput::make('transaction_reference')
                            ->label(__('admin.transaction_reference'))
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('payment_proof')
                            ->label(__('admin.payment_proof'))
                            ->directory('payments/proofs')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->nullable()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label(__('admin.paid_at'))
                            ->seconds(false),

                        Forms\Components\Textarea::make('notes')
                            ->label(__('admin.notes'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('method')
            ->columns([
                Tables\Columns\TextColumn::make('method')
                    ->label(__('admin.payment_method'))
                    ->badge()
                    ->formatStateUsing(fn($state) => __('admin.' . $state)),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('admin.payment_status'))
                    ->badge()
                    ->formatStateUsing(fn($state) => __('admin.payment_' . $state))
                    ->color(fn($state) => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('admin.amount'))
                    ->formatStateUsing(fn($state) => number_format((float) $state, 2, '.', ',') . ' EGP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('transaction_reference')
                    ->label(__('admin.transaction_reference'))
                    ->copyable()
                    ->placeholder('-'),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label(__('admin.payment_proof'))
                    ->disk('public')
                    ->square()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label(__('admin.paid_at'))
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('admin.add_payment'))
                    ->after(function ($record): void {
                        $this->syncOrderPaymentStatus();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record): void {
                        $this->syncOrderPaymentStatus();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->after(function (): void {
                        $this->syncOrderPaymentStatus();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function (): void {
                        $this->syncOrderPaymentStatus();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function syncOrderPaymentStatus(): void
    {
        $order = $this->ownerRecord->fresh('payments');

        $paidTotal = $order->payments
            ->where('status', 'paid')
            ->sum(fn($payment) => (float) $payment->amount);

        $hasPending = $order->payments
            ->where('status', 'pending')
            ->isNotEmpty();

        $hasFailed = $order->payments
            ->where('status', 'failed')
            ->isNotEmpty();

        $hasRefunded = $order->payments
            ->where('status', 'refunded')
            ->isNotEmpty();

        $paymentStatus = 'unpaid';

        if ($paidTotal >= (float) $order->grand_total && (float) $order->grand_total > 0) {
            $paymentStatus = 'paid';
        } elseif ($hasPending) {
            $paymentStatus = 'pending';
        } elseif ($hasRefunded) {
            $paymentStatus = 'refunded';
        } elseif ($hasFailed) {
            $paymentStatus = 'failed';
        }

        $latestPayment = $order->payments()->latest()->first();

        $order->update([
            'payment_status' => $paymentStatus,
            'payment_method' => $latestPayment?->method ?? $order->payment_method,
        ]);
        if ($paymentStatus === 'paid') {
            app(CouponService::class)->markCouponAsUsedForOrder($order->fresh());
        }
    }
}