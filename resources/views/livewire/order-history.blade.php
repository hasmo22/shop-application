<div class="mb-8">
    <h2 class="text-xl font-bold mb-4">Your Orders</h2>

    @if(empty($orders))
        <p class="text-gray-500">No orders placed yet.</p>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="border p-4 rounded-lg shadow-md">
                    <h3 class="font-semibold text-lg">Order #{{ $order['id'] }}</h3>
                    <p class="text-gray-500">Status: {{ ucfirst($order['status']) }}</p>
                    
                    <ul class="mt-2 space-y-2">
                        @foreach($order['items'] as $item)
                            <li class="flex justify-between">
                                <span>{{ $item['product']['name'] }} (x{{ $item['quantity'] }})</span>
                                <span class="font-bold">£{{ number_format($item['price'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>
