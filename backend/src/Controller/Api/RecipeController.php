<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\Recipe\Recipe as RecipeDto;
use App\Dto\Recipe\RecipeListItem;
use App\Flex\Recipe\RecipeFactory;
use App\Repository\RecipeRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/recipe', name: 'recipe_')]
#[OA\Tag('Recipe')]
final class RecipeController extends AbstractController
{
    public function __construct(
        private readonly RecipeFactory $factory,
        private readonly RecipeRepository $repository, private readonly RecipeRepository $recipeRepository,
    ) {
    }

    /** @return iterable<RecipeListItem> */
    #[Route(path: '/', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List recipes', content: new Model(type: RecipeListItem::class))]
    public function list(): iterable
    {
        return $this->repository->getRecipeList();
    }

    #[Route(path: '/{vendor}/{packageName}/{version}', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Show recipe', content: new Model(type: RecipeDto::class))]
    #[OA\Response(response: 404, description: 'Recipe not found')]
    public function show(string $vendor, string $packageName, string $version): RecipeDto
    {
        return $this->repository->getRecipe($vendor, $packageName, $version) ?? throw $this->createNotFoundException();
    }

    #[Route(path: '/', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Create recipe')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function create(#[MapRequestPayload] RecipeDto $createRecipeDto): void
    {
        $this->factory->create($createRecipeDto);
    }

    #[Route(path: '/{vendor}/{packageName}/{version}', methods: ['DELETE'])]
    #[OA\Response(response: 204, description: 'Delete recipe')]
    #[OA\Response(response: 404, description: 'Recipe not found')]
    public function delete(string $vendor, string $packageName, string $version): void
    {
        $this->recipeRepository->delete($vendor, $packageName, $version);
    }
}
