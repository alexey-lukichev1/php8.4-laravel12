<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(3),
            'content' => $this->faker->paragraphs(5, true),
            'published_at' => $this->faker->optional(0.7)->dateTimeBetween('-2 years', 'now'), // 70% будут иметь дату публикации
            'progress' => (string) $this->faker->randomElement(['0', '10', '20', '30', '40', '50', '60', '70', '80', '90', '100']),
            'views' => $this->faker->numberBetween(0, 10000),
            'release_date' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'category_id' => $this->faker->optional(0.8)->randomElement(Category::pluck('id')->toArray()), // 80% с категорией
        ];
    }

    /**
     * Состояние: неопубликованная книга
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }

    /**
     * Состояние: опубликованная книга
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Состояние: популярная книга (много просмотров)
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'views' => $this->faker->numberBetween(50000, 200000),
        ]);
    }
}
