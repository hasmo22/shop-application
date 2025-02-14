<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CartRepository;

class CartController extends Controller
{
    /**
     * @var CartRepository
     */
    protected CartRepository $cartRepository;

    /**
     * CartController constructor.
     *
     * @param CartRepository $cartRepository
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Retrieve the authenticated user's cart.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $cart = $this->cartRepository->getCart(Auth::id());
        return response()->json($cart);
    }

    /**
     * Add a product to the authenticated user's cart.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorised'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer'
        ]);

        $cart = $this->cartRepository->addToCart(Auth::id(), $request->product_id, $request->quantity);
        return response()->json($cart);
    }

    /**
     * Remove a specific product from the authenticated user's cart.
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function delete(int $productId): JsonResponse
    {
        $cart = $this->cartRepository->removeFromCart(Auth::id(), $productId);
        return response()->json($cart);
    }
}
