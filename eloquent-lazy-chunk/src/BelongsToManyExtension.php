<?php
declare(strict_types=1);

namespace N1215\EloquentLazyChunk;

use Closure;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class BelongsToExtension
 * @package N1215\EloquentLazyChunk
 *
 */
class BelongsToManyExtension
{
    /**
     * @return Closure
     */
    public static function lazyChunk(): Closure
    {
        return function(int $count) {
            /** @var BelongsToMany $this */
            $this->query->addSelect($this->shouldSelect());
            return $this->query->lazyChunk($count)
                ->tapEach(function ($results) {
                    $this->hydratePivotRelation($results->all());
                });
        };
    }

    /**
     * @return Closure
     */
    public static function lazyChunkById(): Closure
    {
        return function (int $count, $column = null, $alias = null) {
            /** @var BelongsToMany $this */
            $this->query->addSelect($this->shouldSelect());

            $column = $column ?? $this->getRelated()->qualifyColumn(
                    $this->getRelatedKeyName()
                );

            $alias = $alias ?? $this->getRelatedKeyName();

            return $this->query->lazyChunkById($count, $column, $alias)
                ->tapEach(function ($results) {
                    $this->hydratePivotRelation($results->all());
                });
        };
    }
}
