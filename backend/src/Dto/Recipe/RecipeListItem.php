<?php

declare(strict_types=1);

namespace App\Dto\Recipe;

final readonly class RecipeListItem
{
    /**
     * @param array<string> $versions
     */
    public function __construct(
        public string $vendor,
        public string $packageName,
        public array $versions
    ) {
    }
}
