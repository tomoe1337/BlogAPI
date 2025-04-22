<?php

namespace Tests\Unit\Services\Post;

use App\Contracts\CacheInterface;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Repositories\Eloquent\PostRepository;
use App\Services\Post\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Service $service;
    private CacheInterface $cache;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cache = app(CacheInterface::class);
        $this->service = new Service(new PostRepository(new Post(), $this->cache));
    }

    #[Test]
    public function it_creates_post()
    {
        // Arrange
        $data = [
            'title' => 'Test Post',
            'content' => 'Test Content',
            'category' => ['title' => 'Test Category']
        ];

        // Act
        $post = $this->service->store($data);

        // Assert
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($data['title'], $post->title);
        $this->assertEquals($data['content'], $post->content);
        $this->assertNotNull($post->category_id);
    }

    #[Test]
    public function it_creates_post_with_category_and_tags()
    {
        // Arrange
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();
        
        $data = [
            'title' => 'Test Post',
            'content' => 'Test Content',
            'category' => ['id' => $category->id],
            'tags' => $tags->map(fn($tag) => ['id' => $tag->id])->toArray()
        ];

        // Act
        $post = $this->service->store($data);

        // Assert
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($category->id, $post->category_id);
        $this->assertCount(2, $post->tags);
    }

    #[Test]
    public function it_updates_post()
    {
        // Arrange
        $post = Post::factory()->create();
        $newCategory = Category::factory()->create();
        $newTags = Tag::factory()->count(2)->create();
        
        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'category' => ['id' => $newCategory->id],
            'tags' => $newTags->map(fn($tag) => ['id' => $tag->id])->toArray()
        ];

        // Act
        $updatedPost = $this->service->update($post, $data);

        // Assert
        $this->assertEquals($data['title'], $updatedPost->title);
        $this->assertEquals($data['content'], $updatedPost->content);
        $this->assertEquals($newCategory->id, $updatedPost->category_id);
        $this->assertCount(2, $updatedPost->tags);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        // Arrange
        $data = [
            'content' => 'Test Content'
        ];

        // Assert & Act
        $this->expectException(ValidationException::class);
        $this->service->store($data);
    }
} 