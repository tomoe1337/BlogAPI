<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(),
            'likes' => random_int(1,2000),
            'is_published' => 1,
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Указывает, что пост не опубликован
     */
    public function unpublished(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => 0,
        ]);
    }

    /**
     * Указывает, что у поста нет категории
     */
    public function withoutCategory(): self
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => null,
        ]);
    }
}
