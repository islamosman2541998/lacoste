<?php

namespace App\Services;

use App\Mail\StoreNotificationMail;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class StoreNotificationService
{
    public function notifyAdminNewOrder(Order $order): void
    {
        $settings = app(NotificationSettingsService::class);

        if (! $settings->shouldNotifyAdminNewOrder()) {
            return;
        }

        $subject = $settings->newOrderEmailSubject();

        $body = app()->getLocale() === 'ar'
            ? "تم إنشاء طلب جديد.\nرقم الطلب: {$order->order_number}\nالعميل: {$order->customer_name}\nالإجمالي: {$order->grand_total} EGP"
            : "A new order has been created.\nOrder Number: {$order->order_number}\nCustomer: {$order->customer_name}\nTotal: {$order->grand_total} EGP";

        Mail::to($settings->adminEmail())
            ->send(new StoreNotificationMail($subject, $body));
    }

    public function notifyAdminNewPayment(Order $order): void
    {
        $settings = app(NotificationSettingsService::class);

        if (! $settings->shouldNotifyAdminNewPayment()) {
            return;
        }

        $subject = app()->getLocale() === 'ar'
            ? 'تم تسجيل دفعة جديدة'
            : 'New Payment Recorded';

        $body = app()->getLocale() === 'ar'
            ? "تم تسجيل دفعة على الطلب: {$order->order_number}\nالعميل: {$order->customer_name}\nالإجمالي: {$order->grand_total} EGP"
            : "A payment has been recorded for order: {$order->order_number}\nCustomer: {$order->customer_name}\nTotal: {$order->grand_total} EGP";

        Mail::to($settings->adminEmail())
            ->send(new StoreNotificationMail($subject, $body));
    }

    public function notifyCustomerOrderStatus(Order $order): void
    {
        $settings = app(NotificationSettingsService::class);

        if (! $settings->shouldNotifyCustomerOrderStatus()) {
            return;
        }

        if (! $order->customer_email) {
            return;
        }

        $subject = app()->getLocale() === 'ar'
            ? 'تحديث حالة طلبك'
            : 'Your Order Status Updated';

        $message = $settings->orderStatusMessage();

        $body = str_replace(
            ['{order_number}', '{status}', '{customer_name}'],
            [
                $order->order_number,
                __('admin.order_' . $order->status),
                $order->customer_name,
            ],
            $message
        );

        Mail::to($order->customer_email)
            ->send(new StoreNotificationMail($subject, $body));
    }

    public function notifyCustomerInvoiceCreated(Invoice $invoice): void
    {
        $settings = app(NotificationSettingsService::class);

        if (! $settings->shouldNotifyCustomerInvoiceCreated()) {
            return;
        }

        if (! $invoice->customer_email) {
            return;
        }

        $subject = app()->getLocale() === 'ar'
            ? 'تم إنشاء فاتورتك'
            : 'Your Invoice Has Been Created';

        $message = $settings->invoiceCreatedMessage();

        $body = str_replace(
            ['{invoice_number}', '{customer_name}', '{total}'],
            [
                $invoice->invoice_number,
                $invoice->customer_name,
                number_format((float) $invoice->grand_total, 2, '.', ',') . ' EGP',
            ],
            $message
        );

        Mail::to($invoice->customer_email)
            ->send(new StoreNotificationMail($subject, $body));
    }
}