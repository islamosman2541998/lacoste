<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Resources\ShipmentResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditShipment extends EditRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('change_shipment_status')
                ->label(__('admin.change_shipment_status'))
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label(__('admin.shipment_status'))
                        ->options([
                            'pending' => __('admin.shipment_pending'),
                            'assigned' => __('admin.shipment_assigned'),
                            'picked_up' => __('admin.shipment_picked_up'),
                            'in_transit' => __('admin.shipment_in_transit'),
                            'delivered' => __('admin.shipment_delivered'),
                            'failed' => __('admin.shipment_failed'),
                            'returned' => __('admin.shipment_returned'),
                            'cancelled' => __('admin.shipment_cancelled'),
                        ])
                        ->required()
                        ->default(fn () => $this->record->status),

                    Forms\Components\Textarea::make('notes')
                        ->label(__('admin.notes'))
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $oldStatus = $this->record->status;
                    $newStatus = $data['status'];

                    if ($oldStatus === $newStatus) {
                        Notification::make()
                            ->title(__('admin.status_not_changed'))
                            ->warning()
                            ->send();

                        return;
                    }

                    $timestamps = [];

                    if ($newStatus === 'assigned') {
                        $timestamps['assigned_at'] = now();
                    }

                    if ($newStatus === 'picked_up') {
                        $timestamps['picked_up_at'] = now();
                    }

                    if ($newStatus === 'in_transit') {
                        $timestamps['in_transit_at'] = now();
                    }

                    if ($newStatus === 'delivered') {
                        $timestamps['delivered_at'] = now();
                    }

                    if ($newStatus === 'failed') {
                        $timestamps['failed_at'] = now();
                    }

                    if ($newStatus === 'returned') {
                        $timestamps['returned_at'] = now();
                    }

                    $this->record->update([
                        'status' => $newStatus,
                        'notes' => $data['notes'] ?? $this->record->notes,
                        ...$timestamps,
                    ]);

                    $this->syncOrderStatusFromShipment($newStatus);

                    Notification::make()
                        ->title(__('admin.shipment_status_updated_successfully'))
                        ->success()
                        ->send();
                }),

            Actions\ViewAction::make()
                ->label(__('admin.view')),

            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }

    protected function syncOrderStatusFromShipment(string $shipmentStatus): void
    {
        $order = $this->record->order;

        if (! $order) {
            return;
        }

        $orderStatus = match ($shipmentStatus) {
            'assigned', 'picked_up', 'in_transit' => 'shipped',
            'delivered' => 'delivered',
            'returned' => 'returned',
            'cancelled' => 'cancelled',
            default => null,
        };

        if (! $orderStatus || $order->status === $orderStatus) {
            return;
        }

        $timestamps = [];

        if ($orderStatus === 'shipped') {
            $timestamps['shipped_at'] = now();
        }

        if ($orderStatus === 'delivered') {
            $timestamps['delivered_at'] = now();
        }

        if ($orderStatus === 'cancelled') {
            $timestamps['cancelled_at'] = now();
        }

        $oldOrderStatus = $order->status;

        $order->update([
            'status' => $orderStatus,
            ...$timestamps,
        ]);

        $order->statusHistories()->create([
            'user_id' => auth()->id(),
            'from_status' => $oldOrderStatus,
            'to_status' => $orderStatus,
            'note' => __('admin.order_status_updated_from_shipment'),
        ]);
    }
}