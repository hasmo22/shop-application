<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use PHPUnit\Framework\Attributes\Test;
use App\Services\PaymentProcessorInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected Cart $cart;
    protected MockInterface $paymentProcessor;
    protected MockInterface $orderRepository;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->product = Product::factory()->create([
            'price' => 100.00,
        ]);

        $this->cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->paymentProcessor = $this->mock(PaymentProcessorInterface::class);
        $this->orderRepository = $this->mock(OrderRepository::class);
    }

    #[Test]
    public function it_creates_an_order_successfully(): void
    {
        $this->actingAs($this->user);
    
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    
        $this->cart->refresh()->load('items.product');
    
        $this->paymentProcessor
            ->shouldReceive('process')
            ->once()
            ->with(200.00)
            ->andReturn(true);
    
        $this->orderRepository
            ->shouldReceive('createOrder')
            ->once()
            ->with(
                $this->user->id,
                Mockery::on(fn ($items) => $items instanceof Collection)
            )
            ->andReturnUsing(function ($userId, $items) {
                return \App\Models\Order::create([
                    'user_id' => $userId,
                    'status' => 'pending',
                ]);
            });
    
        $response = $this->postJson('/api/orders');
    
        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['user_id' => $this->user->id]);
    }
    

    #[Test]
    public function it_returns_unauthorised_if_user_not_authenticated(): void
    {
        Auth::logout();

        $response = $this->postJson('/api/orders');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    #[Test]
    public function it_fails_if_cart_is_empty(): void
    {
        $this->actingAs($this->user);

        Cart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->postJson('/api/orders');

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Cart is empty']);
    }

    #[Test]
    public function it_fails_if_payment_fails(): void
    {
        $this->actingAs($this->user);

        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $this->cart->refresh()->load('items.product');

        $this->assertNotEmpty($this->cart->items, 'Cart should contain items before order processing.');
        $this->assertDatabaseHas('cart_items', ['cart_id' => $this->cart->id]);

        $this->paymentProcessor
            ->shouldReceive('process')
            ->once()
            ->with(200.00)
            ->andReturn(false);

        $response = $this->postJson('/api/orders');

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Payment failed']);
    }
}
