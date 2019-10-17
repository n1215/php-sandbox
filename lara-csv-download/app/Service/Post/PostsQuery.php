<?php
declare(strict_types=1);

namespace App\Service\Post;

use App\Models\Post;
use Illuminate\Support\Enumerable;

/**
 * Class PostsQuery
 * @package App\Service\Post
 */
class PostsQuery
{
    private const CHUNK_COUNT = 100;

    /**
     * @return Enumerable<Post>
     */
    public function get(): Enumerable
    {
        return Post::query()
            ->with(['author'])
            ->lazyChunk(self::CHUNK_COUNT)
            ->collapse();
    }
}
