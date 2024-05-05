<?php

declare(strict_types=1);

namespace App\EventListener\Entity\Recipe;

use App\Entity\Recipe;
use App\Flex\Index\IndexLoaderCachingDecorator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsEntityListener(event: Events::postPersist, method: 'onRecipeChange', entity: Recipe::class)]
#[AsEntityListener(event: Events::postRemove, method: 'onRecipeChange', entity: Recipe::class)]
final readonly class IndexCacheClearListener
{
    public function __construct(
        private TagAwareCacheInterface $flexCache,
    ) {
    }

    /**
     * @param LifecycleEventArgs<EntityManagerInterface> $eventArgs
     */
    public function onRecipeChange(Recipe $recipe, LifecycleEventArgs $eventArgs): void
    {
        $this->flexCache->invalidateTags([IndexLoaderCachingDecorator::INDEX_CACHE_KEY]);
    }
}
