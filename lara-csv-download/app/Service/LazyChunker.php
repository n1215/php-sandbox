<?php
declare(strict_types=1);

namespace App\Service;

use Illuminate\Support\LazyCollection;

/**
 * chunk()でもLazyCollectionしたい
 * @package App\Service
 */
class LazyChunker
{
    /**
     * @var callable
     */
    private $callback;


    /**
     * @var int
     */
    private $count;

    /**
     * @param int $count
     * @param callable $callback
     */
    public function __construct(int $count, callable $callback)
    {
        $this->callback = $callback;
        $this->count = $count;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @return LazyCollection
     */
    public function chunk($query): LazyCollection
    {
        return LazyCollection::make(function () use ($query) {

            $this->enforceOrderBy($query);

            $page = 1;

            do {
                $results = $query->forPage($page, $this->count)->get();

                $countResults = $results->count();

                if ($countResults == 0) {
                    break;
                }

                $generator = ($this->callback)($results, $page);
                assert($generator instanceof \Traversable);

                yield from $generator;
                unset($results);

                $page++;
            } while ($countResults == $this->count);
        });
    }

    /**
     * orderBy強制
     * @param $query
     */
    private function enforceOrderBy($query)
    {
        if ($query instanceof \Illuminate\Database\Eloquent\Builder) {
            if (empty($query->orders) && empty($query->unionOrders)) {
                $query->orderBy($query->getModel()->getQualifiedKeyName(), 'asc');
            }
            return;
        }

        if ($query instanceof \Illuminate\Database\Query\Builder) {
            if (empty($query->orders) && empty($query->unionOrders)) {
                throw new \RuntimeException('You must specify an orderBy clause when using this function.');
            }
            return;
        }

        throw new \InvalidArgumentException('argument 1 `$query` must be an instance of Query Builder or Eloquent Builder');
    }
}
