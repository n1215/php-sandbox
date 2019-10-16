<?php
declare(strict_types=1);

namespace App\Service\Post;

use App\Models\Post;
use App\Service\LazyChunker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;

/**
 * Class PostsQuery
 * @package App\Service\Post
 */
class PostsQuery
{
    private const CHUNK_COUNT = 100;

    /**
     * @var LazyChunker
     */
    private $lazyChunker;

    public function __construct()
    {
        $this->lazyChunker = new LazyChunker(self::CHUNK_COUNT);
    }

    /**
     * @return Enumerable<Post>
     */
    public function get(): Enumerable
    {
        $query = Post::query();
        return $this->lazyChunker
            ->chunk($query)
            ->tapEach(function (Collection $posts) {
                $posts->load('author');
            })
            ->collapse();
    }
}
