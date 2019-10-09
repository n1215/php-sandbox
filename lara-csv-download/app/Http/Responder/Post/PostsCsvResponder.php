<?php
declare(strict_types=1);

namespace App\Http\Responder\Post;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Enumerable;
use SplFileObject;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class PostsCsvResponder
 * @package App\Http\Responder\Post
 */
class PostsCsvResponder
{
    private const CSV_HEADERS = [
        'id',
        'title',
        'body',
        'author_name'
    ];

    /**
     * @param Enumerable<Post> $posts
     * @return StreamedResponse
     */
    public function respond(Enumerable $posts): StreamedResponse
    {

        $timestamp = Carbon::now()->format('YmdHis');

        return response()->streamDownload(
            function () use ($posts) {
                $file = new SplFileObject('php://output', 'w');
                $file->fputcsv(self::CSV_HEADERS);
                foreach($posts as $index => $post) {
                    // LazyCollectionの挙動確認用
//                    info('put row of post no.' . ($index + 1));
                    $file->fputcsv($this->postToCsvRow($post));
                }
            },
            "posts_{$timestamp}.csv",
            ['Content-Type' => 'application/octet-stream']
        );
    }

    /**
     * @param Post $post
     * @return array
     */
    private function postToCsvRow(Post $post): array
    {
        return [
            $post->id,
            $post->title,
            $post->body,
            $post->author->name,
        ];
    }
}
