<?php

namespace App\Livewire\Site;

use App\Models\Cart;
use Livewire\Component;

class CartCounter extends Component
{
    protected $listeners = [
        'cart-updated' => '$refresh',
    ];

    public function getCartCountProperty(): int
    {
        $sessionId = session()->getId();

        $cart = Cart::query()
            ->withCount('items')
            ->where('session_id', $sessionId)
            ->whereNull('customer_id')
            ->where('status', 'active')
            ->latest()
            ->first();

        return (int) ($cart?->items_count ?? 0);
    }

    public function render()
    {
        return view('livewire.site.cart-counter');
    }
}