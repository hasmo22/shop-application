<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ProductRepository $productRepository;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = new ProductRepository();

        $this->product = Product::factory()->create([
            'name' => 'Sample Product',
            'description' => 'A sample product description',
            'price' => 99.99,
        ]);
    }

    #[Test]
    public function it_retrieves_all_products(): void
    {
        Product::factory()->count(2)->create();

        $products = $this->productRepository->getAllProducts();

        $this->assertCount(3, $products);
    }

    #[Test]
    public function it_retrieves_a_product_by_id(): void
    {
        $retrievedProduct = $this->productRepository->getProductById($this->product->id);

        $this->assertNotNull($retrievedProduct);
        $this->assertEquals($this->product->id, $retrievedProduct->id);
        $this->assertEquals('Sample Product', $retrievedProduct->name);
    }

    #[Test]
    public function it_creates_a_new_product(): void
    {
        $data = [
            'name' => 'New Product',
            'description' => 'A new product description.',
            'price' => 49.99,
        ];

        $newProduct = $this->productRepository->createProduct($data);

        $this->assertInstanceOf(Product::class, $newProduct);
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    #[Test]
    public function it_throws_an_exception_when_product_not_found(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->productRepository->getProductById(999);
    }
}
