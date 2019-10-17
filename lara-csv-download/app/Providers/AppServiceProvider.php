<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $lazyChunk = function (int $count) : LazyCollection {
            return LazyCollection::make(function () use ($count) {
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
        QueryBuilder::macro('lazyChunk', $lazyChunk);
        EloquentBuilder::macro('lazyChunk', $lazyChunk);

        $lazyChunkById = function (int $count, $column = null, $alias = null) : LazyCollection {

            return LazyCollection::make(function () use ($count, $column, $alias) {
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
        QueryBuilder::macro('lazyChunkById', $lazyChunkById);
        EloquentBuilder::macro('lazyChunkById', $lazyChunkById);

        BelongsToMany::macro('lazyChunk', function (int $count): LazyCollection {
            $this->query->addSelect($this->shouldSelect());
            return $this->query->lazyChunk($count)
                ->tapEach(function ($results) {
                    $this->hydratePivotRelation($results->all());
                });
        });
        BelongsToMany::macro('lazyChunkById', function (int $count, $column = null, $alias = null): LazyCollection {

            $this->query->addSelect($this->shouldSelect());

            $column = $column ?? $this->getRelated()->qualifyColumn(
                    $this->getRelatedKeyName()
                );

            $alias = $alias ?? $this->getRelatedKeyName();

            return $this->query->lazyChunkById($count, $column, $alias)
                ->tapEach(function ($results) {
                    $this->hydratePivotRelation($results->all());
                });
        });

        HasManyThrough::macro('lazyChunk', function (int $count): LazyCollection {
            return $this->prepareQueryBuilder()->lazyChunk($count);
        });

        HasManyThrough::macro('lazyChunkById', function (int $count, $column = null, $alias = null): LazyCollection {

            $column = $column ?? $this->getRelated()->getQualifiedKeyName();

            $alias = $alias ?? $this->getRelated()->getKeyName();

            return $this->prepareQueryBuilder()->lazyChunkById($count, $column, $alias);
        });
    }
}
