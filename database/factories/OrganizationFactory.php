<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        $name = fake()->unique()->company().' Farm';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'type' => fake()->randomElement(['desa', 'gapoktan', 'koperasi', 'komunitas']),
            'region' => fake()->city(),
            'address' => fake()->address(),
            'status' => 'active',
            'approved_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (): array => [
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => null,
        ]);
    }
}
