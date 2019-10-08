<?php
declare(strict_types=1);

namespace App\Service\Post;

use App\Models\Post;
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

            $page = 1;

            do {
                $posts = $query->forPage($page, self::CHUNK_COUNT)->get();

                $countResults = $posts->count();

                if ($countResults == 0) {
                    break;
                }

                $posts->load('author');
                foreach($posts as $post) {
                    yield $post;
                }

                unset($results);

                $page++;
            } while ($countResults == self::CHUNK_COUNT);

        }));
    }
}
