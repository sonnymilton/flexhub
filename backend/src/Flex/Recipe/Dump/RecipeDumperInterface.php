<?php

declare(strict_types=1);

namespace App\Flex\Recipe\Dump;

interface RecipeDumperInterface
{
    public function dump(string $filename, DumpRecipeContext $context): void;
}
