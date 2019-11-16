<?php
declare(strict_types=1);

namespace N1215\EloquentLazyChunk\Providers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\ServiceProvider;
use N1215\EloquentLazyChunk\BelongsToManyExtension;
use N1215\EloquentLazyChunk\HasManyThroughExtension;
use N1215\EloquentLazyChunk\QueryBuilderExtension;

/**
 * Class LazyChunkServiceProvider
 * @package N1215\EloquentLazyChunk\Providers
 */
class LazyChunkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        (new QueryBuilderExtension())->install();
        (new BelongsToManyExtension())->install();
        (new HasManyThroughExtension())->install();
    }
}
