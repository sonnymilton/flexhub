<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\Recipe\Recipe as RecipeDto;
use App\Dto\Recipe\RecipeListItem;
use App\Flex\Recipe\RecipeFactory;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/recipe', )]
final class RecipeController extends AbstractController
{
    public function __construct(
        private readonly RecipeFactory $factory,
        private readonly RecipeRepository $repository, private readonly RecipeRepository $recipeRepository,
    ) {
    }

    /** @return iterable<RecipeListItem> */
    #[Route(path: '/', methods: ['GET'])]
    public function list(): iterable
    {
        return $this->repository->getRecipeList();
    }

    #[Route(path: '/{vendor}/{packageName}/{version}', methods: ['GET'])]
    public function show(string $vendor, string $packageName, string $version): RecipeDto
    {
        return $this->repository->getRecipe($vendor, $packageName, $version) ?? throw $this->createNotFoundException();
    }

    #[Route(path: '/', methods: ['POST'])]
    public function create(#[MapRequestPayload] RecipeDto $createRecipeDto): void
    {
        $this->factory->create($createRecipeDto);
    }

    #[Route(path: '/{vendor}/{packageName}/{version}', methods: ['DELETE'])]
    public function delete(string $vendor, string $packageName, string $version): void
    {
        $this->recipeRepository->delete($vendor, $packageName, $version);
    }
}
