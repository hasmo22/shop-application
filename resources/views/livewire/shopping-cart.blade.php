<div class="bg-white rounded-lg shadow-lg p-6">
    @if(empty($cart['items']))
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="flow-root">
            <ul role="list" class="-my-6 divide-y divide-gray-200">
                @foreach($cart['items'] as $item)
                    <li class="flex py-6">
                        <div class="flex-1 ml-4">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="text-base font-medium text-gray-900">
                                        {{ $item['product']['name'] }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        £{{ number_format($item['product']['price'], 2) }}
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex items-center border rounded-lg">
                                        <button 
                                            wire:click="updateQuantity({{ $item['product_id'] }}, -1)"
                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-lg"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                        >
                                            -
                                        </button>
                                        <span class="px-3 py-1 text-gray-600 border-x">
                                            {{ $item['quantity'] }}
                                        </span>
                                        <button 
                                            wire:click="updateQuantity({{ $item['product_id'] }}, 1)"
                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-lg"
                                        >
                                            +
                                        </button>
                                    </div>
                                    <button 
                                        wire:click="removeItem({{ $item['product_id'] }})"
                                        class="ml-4 text-red-500 hover:text-red-700"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="border-t border-gray-200 mt-6 pt-6">
            <div class="flex justify-between text-base font-medium text-gray-900">
                <p>Subtotal</p>
                <p>£{{ number_format($cartTotal, 2) }}</p>
            </div>
            <div class="mt-6">
            <button
                wire:click="checkout"
                class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-black bg-blue-600 hover:bg-blue-700"
            >
                Checkout
            </button>
        </div>

            {{-- Add flash messages for feedback --}}
            @if (session()->has('message'))
                <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            <div class="mt-6 flex justify-center text-sm text-center text-gray-500">
                <p>
                    or
                    <a href="{{ route('dashboard') }}" class="text-blue-600 font-medium hover:text-blue-500">
                        Continue Shopping
                        <span aria-hidden="true"> &rarr;</span>
                    </a>
                </p>
            </div>
        </div>
    @endif
</div>