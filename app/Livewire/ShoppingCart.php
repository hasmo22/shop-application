<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ShoppingCart extends Component
{
    /**
     * The cart contents.
     *
     * @var array
     */
    public array $cart = [];

    /**
     * The total cart value.
     *
     * @var float
     */
    public float $cartTotal = 0;

    /**
     * Lifecycle hook to fetch the cart when the component is mounted.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->fetchCart();
    }

    /**
     * Fetch the current user's cart.
     *
     * @return void
     */
    #[On('cart-updated')]
    public function fetchCart(): void
    {
        $response = Http::withToken(Auth::user()->createToken('API Token')->plainTextToken)
                        ->get(url('/api/cart'));

        $this->cart = $response->json();

        // Calculate total cart value
        if (!empty($this->cart['items'])) {
            $this->cartTotal = collect($this->cart['items'])->sum(fn($item) => $item['product']['price'] * $item['quantity']);
        }
    }

    /**
     * Update the quantity of a product in the cart.
     *
     * @param int $productId
     * @param int $change
     * @return void
     */
    public function updateQuantity(int $productId, int $change): void
    {
        logger('Updating quantity:', ['product_id' => $productId, 'change' => $change]);

        $response = Http::withToken(Auth::user()->createToken('API Token')->plainTextToken)
            ->post(url('/api/cart'), [
                'product_id' => $productId,
                'quantity' => $change > 0 ? 1 : -1 
            ]);

        if ($response->successful()) {
            $this->fetchCart();
        }
    }

    /**
     * Remove a product from the cart.
     *
     * @param int $productId
     * @return void
     */
    public function removeItem(int $productId): void
    {
        $response = Http::withToken(Auth::user()->createToken('API Token')->plainTextToken)
            ->delete(url("/api/cart/{$productId}"));

        if ($response->successful()) {
            $this->fetchCart();
        }
    }

    /**
     * Handle the checkout process.
     *
     * @return void
     */
    public function checkout(): void
    {
        logger('Starting checkout process');
        
        try {
            $response = Http::withToken(Auth::user()->createToken('API Token')->plainTextToken)
                ->post(url('/api/orders'));

            if ($response->successful()) {
                $this->fetchCart();
                session()->flash('message', 'Order placed successfully!');
                logger('Checkout successful');
            } else {
                session()->flash('error', 'There was an error processing your order.');
                logger('Checkout failed', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            logger('Checkout exception', ['error' => $e->getMessage()]);
            session()->flash('error', 'There was an error processing your order.');
        }
    }

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.shopping-cart');
    }
}
