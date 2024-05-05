<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Recipe\File;
use App\Dto\Recipe\Manifest;
use App\Dto\Recipe\Recipe as RecipeDto;
use App\Dto\Recipe\RecipeListItem;
use App\Entity\Recipe;
use App\Flex\Recipe\Dump\RecipeRemoverInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
final class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly RecipeRemoverInterface $recipeRemover,
        ManagerRegistry $registry,
        string $entityClass = Recipe::class
    ) {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @return iterable<RecipeListItem>
     */
    public function getRecipeList(): iterable
    {
        $qb = $this->createQueryBuilder('recipe')
            ->select('recipe.vendor', 'recipe.packageName')
            ->addSelect('json_agg(recipe.version) as versions')
            ->groupBy('recipe.vendor', 'recipe.packageName')
        ;

        /** @var array<array{vendor: non-empty-string, packageName: non-empty-string, versions: non-empty-string}> $result */
        $result = $qb->getQuery()->toIterable();

        foreach ($result as $item) {
            /** @var array<string> $versions */
            $versions = json_decode($item['versions'], true);

            /** @var callable(string, string):int $comparator */
            $comparator = version_compare(...);

            usort($versions, $comparator);

            yield new RecipeListItem($item['vendor'], $item['packageName'], $versions);
        }
    }

    public function getRecipe(string $vendor, string $packageName, string $version): ?RecipeDto
    {
        $qb = $this->createQueryBuilder('recipe')
            ->select('recipe.id', 'recipe.vendor', 'recipe.packageName', 'recipe.version')
            ->addSelect(sprintf('new %s(
                 recipe.manifest.bundle,
                 recipe.manifest.bundleEvn,
                 recipe.manifest.copyFromRecipe,
                 recipe.manifest.copyFromPackage,
                 recipe.manifest.env,
                 recipe.manifest.gitignore,
                 recipe.manifest.composerScripts
             ) as manifest', Manifest::class))
            ->where('recipe.vendor = :vendor')
            ->andWhere('recipe.packageName = :packageName')
            ->andWhere('recipe.version = :version')
            ->setParameter('vendor', $vendor)
            ->setParameter('packageName', $packageName)
            ->setParameter('version', $version);

        /** @var array{id: Uuid, vendor: string, packageName: string, version: string, manifest: Manifest}|null $result */
        $result = $qb->getQuery()->getOneOrNullResult();

        if (null === $result) {
            return null;
        }

        $files = $this
            ->createQueryBuilder('recipe')
            ->select(sprintf('new %s(file.path, file.executable, file.content)', File::class))
            ->join('recipe.files', 'file')
            ->where('recipe.id = :recipeId')
            ->setParameter('recipeId', $result['id'])
            ->getQuery()
            ->getResult();

        return new RecipeDto(
            $result['vendor'],
            $result['packageName'],
            $result['version'],
            $result['manifest'],
            $files,
        );
    }

    public function delete(string $vendor, string $packageName, string $version): void
    {
        $criteria = ['vendor' => $vendor, 'packageName' => $packageName, 'version' => $version];
        $recipe = $this->findOneBy($criteria) ?? throw EntityNotFoundException::fromClassNameAndIdentifier(Recipe::class, $criteria);

        $recipe->removeAssociatedFile($this->recipeRemover);

        $this->getEntityManager()->remove($recipe);
    }
}
