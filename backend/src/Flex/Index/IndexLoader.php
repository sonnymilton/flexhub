<?php

declare(strict_types=1);

namespace App\Flex\Index;

use App\Repository\RecipeRepository;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(IndexLoaderInterface::class)]
final readonly class IndexLoader implements IndexLoaderInterface
{
    public function __construct(
        private RecipeRepository $recipeRepository,
    ) {
    }

    public function load(string $host): array
    {
        $index = [
            'recipes' => [],
            'branch' => 'main',
            'is_contrib' => true,
            '_links' => [
                'repository' => "$host/flex",
                'origin_template' => "{package}:{version}@$host/flex:main",
                'recipe_template' => "$host/flex/{package_dotted}.{version}.json",
            ],
        ];

        foreach ($this->recipeRepository->getRecipeList() as $recipe) {
            $index['recipes']["{$recipe->vendor}/{$recipe->packageName}"] = $recipe->versions;
        }

        return $index;
    }
}
