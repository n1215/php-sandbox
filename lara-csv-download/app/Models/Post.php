<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Post
 * @package App\Models
 *
 * @property int $id ID
 * @property string $title タイトル
 * @property string $body 本文
 * @property string $author_id 著者ID
 * @property Carbon $created_at 作成日時
 * @property Carbon $updated_at 更新日時
 * @property-read Author $author 著者
 */
class Post extends Model
{
    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
