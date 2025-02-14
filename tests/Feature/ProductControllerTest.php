<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Mockery\MockInterface;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected MockInterface $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 50.00,
        ]);

        $this->productRepository = $this->mock(ProductRepository::class);
    }

    #[Test]
    public function it_retrieves_all_products(): void
    {
        $products = Product::factory()->count(2)->create();
        $this->productRepository
            ->shouldReceive('getAllProducts')
            ->once()
            ->andReturn($products->push($this->product));

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_retrieves_a_specific_product(): void
    {
        $this->productRepository
            ->shouldReceive('getProductById')
            ->once()
            ->with($this->product->id)
            ->andReturn($this->product);

        $response = $this->getJson("/api/products/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->product->id,
                'name' => 'Test Product',
                'description' => 'This is a test product.',
                'price' => 50.00,
            ]);
    }

    #[Test]
    public function it_returns_404_when_product_not_found(): void
    {
        $this->productRepository
            ->shouldReceive('getProductById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $response = $this->getJson('/api/products/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_creates_a_new_product_successfully(): void
    {
        $payload = [
            'name' => 'New Product',
            'description' => 'A new product description.',
            'price' => 99.99,
        ];
    
        $product = new Product($payload);
        $product->id = 1;
    
        $this->productRepository
            ->shouldReceive('createProduct')
            ->once()
            ->with($payload)
            ->andReturn($product);
    
        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'id' => 1,
                'name' => 'New Product',
                'description' => 'A new product description.',
                'price' => 99.99,
            ]);
    }

    #[Test]
    public function it_fails_to_create_a_product_with_invalid_data(): void
    {
        $payload = [
            'description' => 'Invalid product',
            'price' => -10,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price']);
    }
}
