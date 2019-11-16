<?php
declare(strict_types=1);

namespace N1215\EloquentLazyChunk;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\LazyCollection;

/**
 * Class QueryBuilderExtension
 * @package N1215\EloquentLazyChunk
 */
class QueryBuilderExtension
{
    /**
     * @return Closure
     */
    public static function lazyChunk(): Closure
    {
        return function(int $count) {
            return LazyCollection::make(function () use ($count) {
                /** @var QueryBuilder|EloquentBuilder $this */
                $this->enforceOrderBy();

                $page = 1;

                do {
                    // We'll execute the query for the given page and get the results. If there are
                    // no results we can just break and return from here. When there are results
                    // we will yield the current chunk of these results here.
                    $results = $this->forPage($page, $count)->get();
                    $countResults = $results->count();
                    if ($countResults == 0) {
                        break;
                    }

                    yield $results;

                    unset($results);

                    $page++;
                } while ($countResults == $count);
            });
        };
    }

    /**
     * @return Closure
     */
    public static function lazyChunkById(): Closure
    {
        return function (int $count, $column = null, $alias = null) {
            return LazyCollection::make(function () use ($count, $column, $alias) {
                /** @var QueryBuilder|EloquentBuilder $this */
                $column = $column ?? $this->defaultKeyName();

                $alias = $alias ?? $column;

                $lastId = null;

                do {
                    $clone = clone $this;

                    // We'll execute the query for the given page and get the results. If there are
                    // no results we can just break and return from here. When there are results
                    // we will yield the current chunk of these results here.
                    $results = $clone->forPageAfterId($count, $lastId, $column)->get();
                    $countResults = $results->count();

                    if ($countResults == 0) {
                        break;
                    }

                    yield $results;

                    $lastId = $results->last()->{$alias};

                    unset($results);
                } while ($countResults == $count);
            });
        };
    }
}
