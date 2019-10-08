<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Author
 * @package App\Models
 *
 * @property int $id ID
 * @property string $name 名前
 * @property Carbon $created_at 作成日時
 * @property Carbon $updated_at 更新日時
 */
class Author extends Model
{
    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
