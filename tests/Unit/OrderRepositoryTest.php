<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private OrderRepository $orderRepository;
    private User $user;
    private Product $product;
    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'price' => 100.00,
        ]);

        $this->cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->orderRepository = new OrderRepository();
    }


    #[Test]
    public function it_creates_an_order_successfully()
    {
        $cartItem = CartItem::factory()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $items = new Collection([$cartItem]);

        $order = $this->orderRepository->createOrder($this->user->id, $items);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 100.00,
        ]);
    }

    #[Test]
    public function it_rolls_back_transaction_if_order_creation_fails(): void
    {
        $items = CartItem::factory()->count(2)->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    
        DB::spy();
    
        $this->expectException(\Exception::class);
        OrderItem::creating(function () {
            throw new \Exception('Some order creation error...');
        });
    
        try {
            $this->orderRepository->createOrder($this->user->id, $items);
        } catch (\Exception $e) {
        }
    
        DB::shouldHaveReceived('rollBack')->once();
        DB::shouldNotHaveReceived('commit');
    
        $this->assertDatabaseMissing('orders', ['user_id' => $this->user->id]);
    }
}
