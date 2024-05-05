<?php

declare(strict_types=1);

namespace App\Messenger\Recipe\Dump;

use App\Flex\Recipe\Dump\RecipeDumperInterface;
use App\Repository\RecipeRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DumpRecipeMessageHandler
{
    public function __construct(
        private RecipeRepository $repository,
        private RecipeDumperInterface $recipeDumper,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(DumpRecipeMessage $message): void
    {
        $recipe = $this->repository->find($message->recipeId);

        if (null === $recipe) {
            $this->logger->warning('Recipe not found', [
                'recipeId' => $message->recipeId,
            ]);

            return;
        }

        $recipe->dump($this->recipeDumper);
    }
}
