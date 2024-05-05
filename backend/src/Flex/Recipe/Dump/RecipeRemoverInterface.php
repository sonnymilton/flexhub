<?php

declare(strict_types=1);

namespace App\Flex\Recipe\Dump;

interface RecipeRemoverInterface
{
    public function remove(string $filename): void;
}
