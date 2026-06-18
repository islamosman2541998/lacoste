<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Log;

class WhatsappNotificationService
{
    public function notifyAdminNewOrder(Order $order): void
    {
        $settings = StoreSetting::current();

        if (! $this->enabled($settings)) {
            return;
        }

        if (! $settings->whatsapp) {
            return;
        }

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $message = app()->getLocale() === 'ar'
            ? "طلب جديد ✅\n\n"
                . "رقم الطلب: {$order->order_number}\n"
                . "العميل: {$order->customer_name}\n"
                . "الهاتف: {$order->customer_phone}\n"
                . "الإجمالي: " . $this->formatMoney((float) $order->grand_total, $settings) . "\n\n"
                . "رابط الطلب:\n{$orderUrl}"
            : "New order ✅\n\n"
                . "Order Number: {$order->order_number}\n"
                . "Customer: {$order->customer_name}\n"
                . "Phone: {$order->customer_phone}\n"
                . "Total: " . $this->formatMoney((float) $order->grand_total, $settings) . "\n\n"
                . "Order link:\n{$orderUrl}";

        $this->sendMessage($settings->whatsapp, $message, 'admin_new_order');
    }

    public function notifyCustomerNewOrder(Order $order): void
    {
        $settings = StoreSetting::current();

        if (! $this->enabled($settings)) {
            return;
        }

        if (! $order->customer_phone) {
            return;
        }

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $message = app()->getLocale() === 'ar'
            ? "مرحبًا {$order->customer_name} 👋\n\n"
                . "تم استلام طلبك بنجاح ✅\n\n"
                . "رقم الطلب: {$order->order_number}\n"
                . "الإجمالي: " . $this->formatMoney((float) $order->grand_total, $settings) . "\n"
                . "حالة الطلب: قيد المراجعة\n\n"
                . "تابع طلبك من هنا:\n{$orderUrl}"
            : "Hello {$order->customer_name} 👋\n\n"
                . "Your order has been received successfully ✅\n\n"
                . "Order Number: {$order->order_number}\n"
                . "Total: " . $this->formatMoney((float) $order->grand_total, $settings) . "\n"
                . "Status: Pending\n\n"
                . "Track your order here:\n{$orderUrl}";

        $this->sendMessage($order->customer_phone, $message, 'customer_new_order');
    }

    public function notifyCustomerOrderStatus(Order $order): void
    {
        $settings = StoreSetting::current();

        if (! $this->enabled($settings)) {
            return;
        }

        if (! $order->customer_phone) {
            return;
        }

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $status = __('admin.order_' . $order->status);

        $message = app()->getLocale() === 'ar'
            ? "تحديث حالة الطلب ✅\n\n"
                . "رقم الطلب: {$order->order_number}\n"
                . "الحالة الجديدة: {$status}\n\n"
                . "تابع الطلب من هنا:\n{$orderUrl}"
            : "Order status updated ✅\n\n"
                . "Order Number: {$order->order_number}\n"
                . "New Status: {$status}\n\n"
                . "Track your order here:\n{$orderUrl}";

        $this->sendMessage($order->customer_phone, $message, 'customer_order_status');
    }

    public function notifyAdminNewPayment(Order $order): void
    {
        $settings = StoreSetting::current();

        if (! $this->enabled($settings)) {
            return;
        }

        if (! $settings->whatsapp) {
            return;
        }

        $orderUrl = route('site.orders.show', [
            'orderNumber' => $order->order_number,
        ]);

        $message = app()->getLocale() === 'ar'
            ? "تم تسجيل دفعة جديدة 💳\n\n"
                . "رقم الطلب: {$order->order_number}\n"
                . "العميل: {$order->customer_name}\n"
                . "الإجمالي: " . $this->formatMoney((float) $order->grand_total, $settings) . "\n\n"
                . "رابط الطلب:\n{$orderUrl}"
            : "New payment recorded 💳\n\n"
                . "Order Number: {$order->order_number}\n"
                . "Customer: {$order->customer_name}\n"
                . "Total: " . $this->formatMoney((float) $order->grand_total, $settings) . "\n\n"
                . "Order link:\n{$orderUrl}";

        $this->sendMessage($settings->whatsapp, $message, 'admin_new_payment');
    }

    private function enabled(StoreSetting $settings): bool
    {
        return (bool) $settings->whatsapp_notifications_enabled
            && filled($settings->whatsapp_api_provider)
            && filled($settings->whatsapp_api_token);
    }

    private function sendMessage(string $phone, string $message, string $type): void
    {
        /*
         |--------------------------------------------------------------------------
         | WhatsApp Provider Integration Placeholder
         |--------------------------------------------------------------------------
         |
         | الإرسال الفعلي هيتضاف هنا بعد اختيار مزود الخدمة.
         | حاليًا بنسجل الرسالة في اللوج فقط عشان نجهز البنية بدون أخطاء.
         |
         */

        Log::info('WhatsApp notification prepared', [
            'type' => $type,
            'phone' => $this->normalizePhone($phone),
            'message' => $message,
        ]);

        /*
         * مثال لاحقًا:
         *
         * match ($settings->whatsapp_api_provider) {
         *     'cloud_api' => $this->sendViaCloudApi($phone, $message),
         *     'twilio' => $this->sendViaTwilio($phone, $message),
         *     'ultramsg' => $this->sendViaUltraMsg($phone, $message),
         * };
         */
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($phone, '00')) {
            $phone = '+' . substr($phone, 2);
        }

        if (str_starts_with($phone, '01')) {
            $phone = '+2' . $phone;
        }

        return $phone;
    }

    private function formatMoney(float $amount, StoreSetting $settings): string
    {
        return number_format($amount, 2, '.', ',') . ' ' . ($settings->currency_symbol ?: 'EGP');
    }
}