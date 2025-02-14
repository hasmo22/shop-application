<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    /**
     * Create a new order.
     *
     * @param int $userId
     * @param Collection $items
     * @return Order
     */
    public function createOrder(int $userId, Collection $items): Order
    {
        DB::beginTransaction();
        $order = Order::create([
            'user_id' => $userId,
            'status' => 'pending'
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price ?? 0,
            ]);
        }

        DB::commit();

        return $order;
    }
}
