<?php

namespace App\Services;

use App\Models\StoreSetting;

class NotificationSettingsService
{
    public function settings(): StoreSetting
    {
        return StoreSetting::current();
    }

    public function emailEnabled(): bool
    {
        return (bool) $this->settings()->email_notifications_enabled;
    }

    public function whatsappEnabled(): bool
    {
        return (bool) $this->settings()->whatsapp_notifications_enabled;
    }

    public function adminEmail(): ?string
    {
        return $this->settings()->admin_notification_email
            ?: $this->settings()->email;
    }

    public function shouldNotifyAdminNewOrder(): bool
    {
        return $this->emailEnabled()
            && (bool) $this->settings()->notify_admin_new_order
            && filled($this->adminEmail());
    }

    public function shouldNotifyAdminNewPayment(): bool
    {
        return $this->emailEnabled()
            && (bool) $this->settings()->notify_admin_new_payment
            && filled($this->adminEmail());
    }

    public function shouldNotifyCustomerOrderStatus(): bool
    {
        return $this->emailEnabled()
            && (bool) $this->settings()->notify_customer_order_status;
    }

    public function shouldNotifyCustomerInvoiceCreated(): bool
    {
        return $this->emailEnabled()
            && (bool) $this->settings()->notify_customer_invoice_created;
    }

    public function newOrderEmailSubject(): string
    {
        return $this->settings()->new_order_email_subject
            ?: __('admin.default_new_order_subject');
    }

    public function orderStatusMessage(): string
    {
        return $this->settings()->order_status_message
            ?: __('admin.default_order_status_message');
    }

    public function invoiceCreatedMessage(): string
    {
        return $this->settings()->invoice_created_message
            ?: __('admin.default_invoice_created_message');
    }

    public function whatsappProvider(): ?string
    {
        return $this->settings()->whatsapp_api_provider;
    }

    public function whatsappToken(): ?string
    {
        return $this->settings()->whatsapp_api_token;
    }
}