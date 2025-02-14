<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\CartRepository;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected Cart $cart;
    protected MockInterface $cartRepository;

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

        $this->cartRepository = $this->mock(CartRepository::class);
    }

    #[Test]
    public function user_can_fetch_cart(): void
    {
        $this->cartRepository
            ->shouldReceive('getCart')
            ->once()
            ->with($this->user->id)
            ->andReturn($this->cart->load('items.product'));

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_add_product_to_cart(): void
    {
        $this->cartRepository
            ->shouldReceive('addToCart')
            ->once()
            ->with($this->user->id, $this->product->id, 1)
            ->andReturn($this->cart->load('items.product'));

        $response = $this->postJson('/api/cart', [
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_update_cart_item_quantity(): void
    {
           $this->cartRepository
            ->shouldReceive('addToCart')
            ->once()
            ->with($this->user->id, $this->product->id, 2)
            ->andReturn($this->cart->refresh()->load('items.product'));
    
        $response = $this->postJson('/api/cart', [
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);
    
        $response->assertStatus(200);
    }    
    

    #[Test]
    public function user_can_delete_cart_item(): void
    {
        $this->cartRepository
            ->shouldReceive('removeFromCart')
            ->once()
            ->with($this->user->id, $this->product->id)
            ->andReturn($this->cart->load('items.product'));

        $response = $this->deleteJson('/api/cart/' . $this->product->id);

        $response->assertStatus(200);
    }
}
