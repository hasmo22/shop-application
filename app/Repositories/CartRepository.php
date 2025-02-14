<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CartRepository
{
    /**
     * Retrieve the user's cart with items and products.
     *
     * @param int $userId
     * @return Cart|null
     */
    public function getCart(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)->with('items.product')->first();
    }

    /**
     * Add a product to the cart or update its quantity.
     *
     * @param int $userId
     * @param int $productId
     * @param int $quantity
     * @return Cart
     */
    public function addToCart(int $userId, int $productId, int $quantity): Cart
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => $newQuantity]);
            }
        } elseif ($quantity > 0) {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cart->load('items.product');
    }

    /**
     * Update the quantity of a product in the cart.
     *
     * @param int $userId
     * @param int $productId
     * @param int $change
     * @return Cart
     */
    public function updateQuantity(int $userId, int $productId, int $change): Cart
    {
        return $this->addToCart($userId, $productId, $change);
    }

    /**
     * Remove a product from the cart.
     *
     * @param int $userId
     * @param int $productId
     * @return Cart|null
     */
    public function removeFromCart(int $userId, int $productId): ?Cart
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            $cart->items()->where('product_id', $productId)->delete();
        }

        return $cart ? $cart->load('items.product') : null;
    }

    /**
     * Clear the user's cart after order placement.
     *
     * @param int $userId
     * @return void
     */
    public function clearCart(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            $cart->items()->delete();
        }
    }
}
