<?php

namespace App\Livewire\Site;

use App\Models\Order;
use Livewire\Component;

class OrderShow extends Component
{
    public string $orderNumber;

    public string $phone = '';

    public bool $verified = false;

    public function mount(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
        $this->verified = $this->canAutoVerify();
    }

    public function verifyOrder(): void
    {
        $this->validate([
            'phone' => ['required', 'string', 'max:50'],
        ], [
            'phone.required' => app()->getLocale() === 'ar'
                ? 'رقم الهاتف مطلوب'
                : 'Phone number is required',
        ]);

        $order = Order::query()
            ->where('order_number', $this->orderNumber)
            ->where('customer_phone', $this->phone)
            ->first();

        if (! $order) {
            $this->dispatch('site-toast',
                type: 'error',
                icon: '!',
                title: app()->getLocale() === 'ar' ? 'بيانات غير صحيحة' : 'Invalid details',
                message: app()->getLocale() === 'ar'
                    ? 'رقم الهاتف لا يطابق بيانات الطلب'
                    : 'Phone number does not match this order'
            );

            return;
        }

        $this->verified = true;

        session()->put('last_order_number', $this->orderNumber);

        $this->dispatch('site-toast',
            type: 'success',
            icon: '✓',
            title: app()->getLocale() === 'ar' ? 'تم التحقق' : 'Verified',
            message: app()->getLocale() === 'ar'
                ? 'تم عرض تفاصيل الطلب'
                : 'Order details are now visible'
        );
    }

    public function statusLabel(?string $status): string
    {
        return match ($status) {
            'pending' => app()->getLocale() === 'ar' ? 'قيد المراجعة' : 'Pending',
            'confirmed' => app()->getLocale() === 'ar' ? 'تم التأكيد' : 'Confirmed',
            'processing' => app()->getLocale() === 'ar' ? 'قيد التجهيز' : 'Processing',
            'shipped' => app()->getLocale() === 'ar' ? 'تم الشحن' : 'Shipped',
            'delivered' => app()->getLocale() === 'ar' ? 'تم التسليم' : 'Delivered',
            'cancelled' => app()->getLocale() === 'ar' ? 'ملغي' : 'Cancelled',
            'returned' => app()->getLocale() === 'ar' ? 'مرتجع' : 'Returned',
            default => $status ?: '-',
        };
    }

    public function statusColor(?string $status): string
    {
        return match ($status) {
            'confirmed', 'delivered' => 'success',
            'processing', 'shipped' => 'info',
            'cancelled', 'returned' => 'danger',
            'pending' => 'warning',
            default => 'gray',
        };
    }

    public function paymentStatusLabel(?string $status): string
    {
        return match ($status) {
            'unpaid' => app()->getLocale() === 'ar' ? 'غير مدفوع' : 'Unpaid',
            'pending' => app()->getLocale() === 'ar' ? 'قيد المراجعة' : 'Pending',
            'paid' => app()->getLocale() === 'ar' ? 'مدفوع' : 'Paid',
            'failed' => app()->getLocale() === 'ar' ? 'فشل الدفع' : 'Failed',
            'refunded' => app()->getLocale() === 'ar' ? 'مسترد' : 'Refunded',
            default => $status ?: '-',
        };
    }

    public function paymentStatusColor(?string $status): string
    {
        return match ($status) {
            'paid' => 'success',
            'pending' => 'info',
            'failed' => 'danger',
            'refunded' => 'gray',
            'unpaid' => 'warning',
            default => 'gray',
        };
    }

    public function paymentMethodLabel(?string $method): string
    {
        return match ($method) {
            'cash_on_delivery' => app()->getLocale() === 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery',
            'bank_transfer' => app()->getLocale() === 'ar' ? 'تحويل بنكي' : 'Bank Transfer',
            'wallet_transfer' => app()->getLocale() === 'ar' ? 'تحويل محفظة' : 'Wallet Transfer',
            'manual' => app()->getLocale() === 'ar' ? 'دفع يدوي' : 'Manual Payment',
            default => $method ?: '-',
        };
    }

    private function canAutoVerify(): bool
    {
        if (session('last_order_number') === $this->orderNumber) {
            return true;
        }

        try {
            $customerId = auth('customer')->id();

            if (! $customerId) {
                return false;
            }

            return Order::query()
                ->where('order_number', $this->orderNumber)
                ->where('customer_id', $customerId)
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function order(): ?Order
    {
        if (! $this->verified) {
            return null;
        }

        return Order::query()
            ->where('order_number', $this->orderNumber)
            ->with([
                'items.product',
                'items.variant',
                'payments',
                'shipments.company',
                'shipments.city',
                'latestInvoice',
                'shippingCity',
                'shippingZone',
            ])
            ->first();
    }

    public function render()
    {
        return view('livewire.site.order-show', [
            'order' => $this->order(),
        ]);
    }
}