<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Tabs Navigation -->
        <div class="mb-8 border-b">
            <ul class="flex border-b">
                <li class="mr-4">
                    <button class="tab-link text-lg font-semibold py-2 px-4 border-b-2 border-transparent focus:outline-none"
                        onclick="openTab(event, 'orders')">
                        Orders
                    </button>
                </li>
                <li>
                    <button class="tab-link text-lg font-semibold py-2 px-4 border-b-2 border-transparent focus:outline-none"
                        onclick="openTab(event, 'products')" id="default-tab">
                        Products
                    </button>
                </li>
            </ul>
        </div>

        <!-- Orders Section -->
        <div id="orders" class="tab-content hidden">
            @livewire('order-history')
        </div>

        <!-- Products Section -->
        <div id="products" class="tab-content">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Our Products</h1>
                <a href="{{ route('cart') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-black font-medium rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    View Cart
                </a>
            </div>
            @livewire('product-list')
        </div>
    </div>

    <!-- Tabs Script -->
    <script>
        function openTab(event, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.add("hidden");
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("border-blue-600");
            }
            document.getElementById(tabName).classList.remove("hidden");
            event.currentTarget.classList.add("border-blue-600");
        }
        document.getElementById("default-tab").click();
    </script>
</x-app-layout>
