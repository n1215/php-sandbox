<?php
declare(strict_types=1);

namespace N1215\EloquentLazyChunk;

use Closure;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class HasManyThroughExtension
 * @package N1215\EloquentLazyChunk
 */
class HasManyThroughExtension
{
    public function install(): void
    {
        HasManyThrough::macro('lazyChunk', self::lazyChunk());
        HasManyThrough::macro('lazyChunkById', self::lazyChunkById());
    }

    /**
     * @return Closure
     */
    private static function lazyChunk(): Closure
    {
        return function(int $count) {
            /** @var HasManyThrough $this */
            return $this->prepareQueryBuilder()->lazyChunk($count);
        };
    }

    /**
     * @return Closure
     */
    private static function lazyChunkById(): Closure
    {
        return function (int $count, $column = null, $alias = null) {
            /** @var HasManyThrough $this */
            $column = $column ?? $this->getRelated()->getQualifiedKeyName();

            $alias = $alias ?? $this->getRelated()->getKeyName();

            return $this->prepareQueryBuilder()->lazyChunkById($count, $column, $alias);
        };
    }
}
