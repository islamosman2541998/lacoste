<?php

namespace App\Services;

use App\Mail\StoreNotificationMail;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\StoreSetting;
use App\Services\NotificationSettingsService;
use Illuminate\Support\Facades\Mail;

class StoreNotificationService
{
    public function notifyAdminNewOrder(Order $order): void
    {
        $settings = app(NotificationSettingsService::class);

        if (! $settings->shouldNotifyAdminNewOrder()) {
            return;
        }

        if (! $settings->adminEmail()) {
            return;
        }

        $subject = $settings->newOrderEmailSubject();

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $body = app()->getLocale() === 'ar'
            ? "تم إنشاء طلب جديد.\n\n"
            . "رقم الطلب: {$order->order_number}\n"
            . "العميل: {$order->customer_name}\n"
            . "الهاتف: {$order->customer_phone}\n"
            . "الإجمالي: " . $this->formatMoney((float) $order->grand_total) . "\n\n"
            . "رابط متابعة الطلب:\n{$orderUrl}"
            : "A new order has been created.\n\n"
            . "Order Number: {$order->order_number}\n"
            . "Customer: {$order->customer_name}\n"
            . "Phone: {$order->customer_phone}\n"
            . "Total: " . $this->formatMoney((float) $order->grand_total) . "\n\n"
            . "Order tracking link:\n{$orderUrl}";

        Mail::to($settings->adminEmail())
            ->send(new StoreNotificationMail(
                $subject,
                $body,
                $orderUrl,
                app()->getLocale() === 'ar' ? 'عرض الطلب' : 'View Order'
            ));
            app(WhatsappNotificationService::class)->notifyAdminNewOrder($order);
    }

    public function notifyCustomerNewOrder(Order $order): void
    {
        $storeSettings = StoreSetting::current();

        if (! $storeSettings->email_notifications_enabled) {
            return;
        }

        if (! $order->customer_email) {
            return;
        }

        $subject = app()->getLocale() === 'ar'
            ? 'تم استلام طلبك بنجاح'
            : 'Your order has been received';

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $body = app()->getLocale() === 'ar'
            ? "مرحبًا {$order->customer_name}،\n\n"
            . "تم استلام طلبك بنجاح ✅\n\n"
            . "رقم الطلب: {$order->order_number}\n"
            . "الإجمالي: " . $this->formatMoney((float) $order->grand_total) . "\n"
            . "حالة الطلب: قيد المراجعة\n\n"
            . "يمكنك متابعة الطلب من هنا:\n{$orderUrl}\n\n"
            . "قد يُطلب منك إدخال رقم الهاتف للتأكيد عند فتح الرابط."
            : "Hello {$order->customer_name},\n\n"
            . "Your order has been received successfully ✅\n\n"
            . "Order Number: {$order->order_number}\n"
            . "Total: " . $this->formatMoney((float) $order->grand_total) . "\n"
            . "Order Status: Pending\n\n"
            . "You can track your order here:\n{$orderUrl}\n\n"
            . "You may be asked to enter your phone number for verification.";

        Mail::to($order->customer_email)
            ->send(new StoreNotificationMail(
                $subject,
                $body,
                $orderUrl,
                app()->getLocale() === 'ar' ? 'متابعة الطلب' : 'Track Order'
            ));
            app(WhatsappNotificationService::class)->notifyCustomerNewOrder($order);
    }

    public function notifyAdminNewPayment(Order $order): void
    {
        $settings = app(NotificationSettingsService::class);

        if (! $settings->shouldNotifyAdminNewPayment()) {
            return;
        }

        if (! $settings->adminEmail()) {
            return;
        }

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $subject = app()->getLocale() === 'ar'
            ? 'تم تسجيل دفعة جديدة'
            : 'New Payment Recorded';

        $body = app()->getLocale() === 'ar'
            ? "تم تسجيل دفعة على الطلب.\n\n"
            . "رقم الطلب: {$order->order_number}\n"
            . "العميل: {$order->customer_name}\n"
            . "الإجمالي: " . $this->formatMoney((float) $order->grand_total) . "\n\n"
            . "رابط متابعة الطلب:\n{$orderUrl}"
            : "A payment has been recorded for an order.\n\n"
            . "Order Number: {$order->order_number}\n"
            . "Customer: {$order->customer_name}\n"
            . "Total: " . $this->formatMoney((float) $order->grand_total) . "\n\n"
            . "Order tracking link:\n{$orderUrl}";

        Mail::to($settings->adminEmail())
            ->send(new StoreNotificationMail($subject, $body));
            app(WhatsappNotificationService::class)->notifyAdminNewPayment($order);
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

        if (! $message) {
            $message = app()->getLocale() === 'ar'
                ? "مرحبًا {customer_name}،\n\nتم تحديث حالة طلبك رقم {order_number} إلى: {status}"
                : "Hello {customer_name},\n\nYour order {order_number} status has been updated to: {status}";
        }

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $body = str_replace(
            ['{order_number}', '{status}', '{customer_name}', '{order_url}'],
            [
                $order->order_number,
                __('admin.order_' . $order->status),
                $order->customer_name,
                $orderUrl,
            ],
            $message
        );

        $body .= app()->getLocale() === 'ar'
            ? "\n\nيمكنك متابعة الطلب من هنا:\n{$orderUrl}"
            : "\n\nYou can track your order here:\n{$orderUrl}";

        Mail::to($order->customer_email)
            ->send(new StoreNotificationMail(
                $subject,
                $body,
                $orderUrl,
                app()->getLocale() === 'ar' ? 'متابعة الطلب' : 'Track Order'
            ));
            app(WhatsappNotificationService::class)->notifyCustomerOrderStatus($order);
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

        if (! $message) {
            $message = app()->getLocale() === 'ar'
                ? "مرحبًا {customer_name}،\n\nتم إنشاء فاتورتك رقم {invoice_number} بإجمالي {total}."
                : "Hello {customer_name},\n\nYour invoice {invoice_number} has been created with total {total}.";
        }

        $body = str_replace(
            ['{invoice_number}', '{customer_name}', '{total}'],
            [
                $invoice->invoice_number,
                $invoice->customer_name,
                $this->formatMoney((float) $invoice->grand_total),
            ],
            $message
        );

        if ($invoice->order) {
            $orderUrl = route('site.orders.show', [
                'orderNumber' => $invoice->order->order_number,
            ]);

            $body .= app()->getLocale() === 'ar'
                ? "\n\nيمكنك متابعة الطلب من هنا:\n{$orderUrl}"
                : "\n\nYou can track your order here:\n{$orderUrl}";
        }

        Mail::to($invoice->customer_email)
            ->send(new StoreNotificationMail($subject, $body));
    }

    private function formatMoney(float $amount): string
    {
        $settings = StoreSetting::current();

        return number_format($amount, 2, '.', ',') . ' ' . ($settings->currency_symbol ?: 'EGP');
    }
}