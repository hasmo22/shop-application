<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ProductList extends Component
{
    /**
     * List of products available in the shop.
     *
     * @var array
     */
    public array $products = [];

    /**
     * Lifecycle hook to fetch products when the component is mounted.
     *
     * @return void
     */
    public function mount(): void
    {
        $response = Http::withToken(Auth::user()->createToken('API Token')->plainTextToken)
                        ->get(url('/api/products'));
        
        if ($response->successful()) {
            $this->products = $response->json();
        }
    }

    /**
     * Add a product to the cart.
     *
     * @param int $productId
     * @return void
     */
    public function addToCart(int $productId): void
    {
        try {
            logger('Attempting to add to cart:', ['product_id' => $productId]);

            $response = Http::withToken(Auth::user()->createToken('API Token')->plainTextToken)
                ->post(url('/api/cart'), [
                    'product_id' => $productId,
                    'quantity' => 1
                ]);

            logger('Cart API Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $this->dispatch('cart-updated');
                session()->flash('message', 'Product added to cart!');
            } else {
                logger('Cart add failed:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                session()->flash('error', 'Failed to add item to cart: ' . $response->body());
            }
        } catch (\Exception $e) {
            logger('Cart add exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.product-list');
    }
}
