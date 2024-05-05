<?php

declare(strict_types=1);

namespace App\Flex\Index;

/**
 * @phpstan-type FlexIndex array{
 *     branch: string,
 *     is_contrib: bool,
 *     _links: array<string,string>,
 *     recipes: array<string, array<string>>
 * }
 */
interface IndexLoaderInterface
{
    /**
     * @return FlexIndex
     */
    public function load(string $host): array;
}
