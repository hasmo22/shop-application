<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $product['name'] }}</h2>
                <p class="text-gray-600 mb-4">{{ $product['description'] }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-900">£{{ number_format($product['price'], 2) }}</span>
                    <button 
                        wire:click="addToCart({{ $product['id'] }})"
                        class="bg-blue-600 text-black px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>