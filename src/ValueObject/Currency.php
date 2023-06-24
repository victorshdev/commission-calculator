<?php

namespace App\ValueObject;

final class Currency
{
    private static array $cache = [];

    private function __construct(private readonly string $name)
    {

    }

    /**
     * @param string $name
     *
     * @return Currency
     */
    public static function create(string $name): Currency
    {
        if (!isset(self::$cache[$name])) {
            self::$cache[$name] = new self($name);
        }

        return self::$cache[$name];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}