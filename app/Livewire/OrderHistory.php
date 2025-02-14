<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Component
{
    /**
     * The user's order history.
     *
     * @var array
     */
    public array $orders = [];

    /**
     * Lifecycle hook to fetch orders when the component is mounted.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->fetchOrders();
    }

    /**
     * Fetch the user's order history from the API.
     *
     * @return void
     */
    public function fetchOrders(): void
    {
        $response = Http::withToken(Auth::user()->createToken('API Token')->plainTextToken)
                        ->get(url('/api/orders'));

        if ($response->successful()) {
            $this->orders = $response->json();
        }
    }

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.order-history');
    }
}
