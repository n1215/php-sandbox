<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responder\Post\PostsCsvResponder;
use App\Service\Post\PostsQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class PostsController
 * @package App\Http\Controllers
 */
class PostsController extends Controller
{
    /**
     * @var PostsQuery
     */
    private $postsQuery;

    /**
     * @var PostsCsvResponder
     */
    private $postsResponder;

    /**
     * PostsController constructor.
     * @param PostsQuery $postsQuery
     * @param PostsCsvResponder $postsResponder
     */
    public function __construct(PostsQuery $postsQuery, PostsCsvResponder $postsResponder)
    {
        $this->postsQuery = $postsQuery;
        $this->postsResponder = $postsResponder;
    }

    /**
     * @return StreamedResponse
     */
    public function download(): StreamedResponse
    {
        // Eager Load確認用
        DB::listen(function ($event) {
            info('query', [$event->sql, $event->bindings, $event->time]);
        });

        return $this->postsResponder->respond($this->postsQuery->get());
    }
}
