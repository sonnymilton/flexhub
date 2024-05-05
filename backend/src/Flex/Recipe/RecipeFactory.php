<?php

declare(strict_types=1);

namespace App\Flex\Recipe;

use App\Dto\Recipe\File as FileDto;
use App\Dto\Recipe\Recipe as RecipeDto;
use App\Entity\File;
use App\Entity\Manifest;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class RecipeFactory
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function create(RecipeDto $dto): Recipe
    {
        $manifestDto = $dto->manifest;

        $manifest = new Manifest(
            bundle: $manifestDto->bundleClass,
            bundleEvn: $manifestDto->bundleEnv,
            copyFromRecipe: $manifestDto->copyFromRecipe,
            copyFromPackage: $manifestDto->copyFromPackage,
            env: $manifestDto->env,
            gitignore: $manifestDto->gitignore,
            composerScripts: $manifestDto->composerScripts,
        );

        $files = array_map(static fn (FileDto $file) => new File($file->path, $file->content, $file->executable), $dto->files);

        $recipe = new Recipe($dto->vendor, $dto->packageName, $dto->version, $manifest, $files);

        $this->em->persist($recipe);

        $recipe->queueDump($this->messageBus);

        return $recipe;
    }
}
