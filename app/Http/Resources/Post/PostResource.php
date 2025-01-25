<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Category\CategoryResourse;
use App\Http\Resources\Tag\TagResourse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'category' => new CategoryResourse($this->category),
            'tags' => TagResourse::collection($this->tags),
        ];
    }
}
