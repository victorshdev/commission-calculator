<?php

namespace App\Interface;

interface BinProviderInterface
{
    public function getCountry(string $bin): string;
}