<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\ProductRepository;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Retrieve all products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->productRepository->getAllProducts());
    }

    /**
     * Retrieve a specific product by ID.
     *
     * @param int $id The ID of the product.
     * @return JsonResponse|HttpResponse
     */
    public function show(int $id): JsonResponse|Response
    {
        $product = $this->productRepository->getProductById($id);
        if ($product) {
            return response()->json($product);
        }
        return response('', 404);
    }

    /**
     * Store a newly created product.
     *
     * @param Request $request The HTTP request containing product data.
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $product = $this->productRepository->createProduct($request->all());

        return response()->json($product, 201);
    }
}
