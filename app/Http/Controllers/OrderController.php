<?php

namespace App\Http\Controllers;

use App\Models\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use App\Services\PaymentProcessorInterface;

class OrderController extends Controller
{
    /**
     * @var PaymentProcessorInterface
     */
    protected PaymentProcessorInterface $paymentProcessor;

    protected OrderRepository $orderRepository;

    /**
     * OrderController constructor.
     *
     * @param PaymentProcessorInterface $paymentProcessor
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        PaymentProcessorInterface $paymentProcessor,
        OrderRepository $orderRepository
    ) {
        $this->paymentProcessor = $paymentProcessor;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Retrieve all orders for the authenticated user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Order::where('user_id', Auth::id())->with('items.product')->get());
    }

    /**
     * Process an order for the authenticated user.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        Log::debug('Starting checkout process');
    
        /** @var User */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorised'], 401);
        }


        Log::debug('Authenticated User ID', ['user_id' => Auth::id()]);

        $cart = $user->cart()->with('items.product')->first();
        Log::debug('Cart Retrieved', ['cart' => $cart ? $cart->toArray() : null]);
        Log::debug('Cart Items Count', ['count' => $cart ? $cart->items->count() : 0]);

        
        if (!$cart || $cart->items->isEmpty()) {
            
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        
        $totalAmount = $cart->items->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);

        if (!$this->paymentProcessor->process($totalAmount)) {
            return response()->json(['message' => 'Payment failed'], 400);
        }
    
        try {
            Log::debug('Creating order');

            $order = $this->orderRepository->createOrder($user->id, $cart->items);
    
            // Clear cart after order is placed
            $cart->items()->delete();

            Log::debug('Order placed successfully', ['order_id' => $order->id]);
    
            return response()->json($order->load('items.product'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create order'], 500);
        }
    }
}