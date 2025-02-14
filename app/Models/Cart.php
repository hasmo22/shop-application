<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['user_id'];

    /**
     * Get the cart items associated with the cart.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
