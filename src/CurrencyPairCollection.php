<?php
namespace App;

use App\ValueObject\CurrencyPair;

readonly class CurrencyPairCollection implements \Countable, \IteratorAggregate
{
    /**
     * @param CurrencyPair[] $elements
     */
    public function __construct(private array $elements = [])
    {

    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }
}