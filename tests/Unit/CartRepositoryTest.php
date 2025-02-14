<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use PHPUnit\Metadata\Test;
use App\Repositories\CartRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CartRepository $cartRepository;
    protected User $user;
    protected Product $product;
    protected Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cartRepository = new CartRepository();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create(['price' => 50.00]);
        $this->cart = Cart::factory()->create(['user_id' => $this->user->id]);
    }

    #[Test]
    public function it_retrieves_the_cart_with_items()
    {
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $retrievedCart = $this->cartRepository->getCart($this->user->id);

        $this->assertNotNull($retrievedCart);
        $this->assertCount(1, $retrievedCart->items);
    }

    #[Test]
    public function it_adds_a_product_to_the_cart()
    {
        $cart = $this->cartRepository->addToCart($this->user->id, $this->product->id, 2);

        $this->assertCount(1, $cart->items);
        $this->assertEquals(2, $cart->items->first()->quantity);
    }

    #[Test]
    public function it_updates_existing_cart_item_quantity()
    {
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $updatedCart = $this->cartRepository->updateQuantity($this->user->id, $this->product->id, 2);

        $this->assertEquals(3, $updatedCart->items->first()->quantity);
    }

    #[Test]
    public function it_removes_a_product_from_the_cart()
    {
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $updatedCart = $this->cartRepository->removeFromCart($this->user->id, $this->product->id);

        $this->assertCount(0, $updatedCart->items);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $this->cart->id]);
    }

    #[Test]
    public function it_clears_the_cart()
    {
        CartItem::factory()->count(2)->create(['cart_id' => $this->cart->id]);

        $this->cartRepository->clearCart($this->user->id);

        $this->assertDatabaseMissing('cart_items', ['cart_id' => $this->cart->id]);
    }
}
