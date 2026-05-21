<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(1000, 9999),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'tax' => $this->faker->randomFloat(2, 0, 100),
            'shipping_cost' => $this->faker->randomFloat(2, 0, 50),
            'status' => 'pending',
            'payment_transaction_id' => null,
            'payment_status' => 'pending',
            'payment_method' => 'CARD',
            'billing_address' => [
                'name' => $this->faker->name,
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'country' => $this->faker->country,
                'postal_code' => $this->faker->postcode,
            ],
            'shipping_address' => [
                'name' => $this->faker->name,
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'country' => $this->faker->country,
                'postal_code' => $this->faker->postcode,
            ],
            'paid_at' => null,
            'ordered_at' => now(),
        ];
    }
}