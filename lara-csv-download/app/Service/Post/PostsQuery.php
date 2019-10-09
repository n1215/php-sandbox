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
     * @return Enumerable<Post>
     */
    public function get(): Enumerable
    {
        return LazyCollection::make((function () {
            $query = Post::query();

            $chunker = (new LazyChunker(self::CHUNK_COUNT, function (Collection $posts) {
                $posts->load('author');
                foreach($posts as $post) {
                    yield $post;
                }
            }));

            return $chunker->chunk($query);
        }));
    }
}
