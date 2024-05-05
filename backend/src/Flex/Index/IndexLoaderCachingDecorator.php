<?php

declare(strict_types=1);

namespace App\Flex\Index;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * @phpstan-import-type FlexIndex from IndexLoaderInterface
 */
#[AsDecorator(IndexLoaderInterface::class)]
final readonly class IndexLoaderCachingDecorator implements IndexLoaderInterface
{
    public const INDEX_CACHE_KEY = 'app_flex_index';

    public function __construct(
        #[AutowireDecorated]
        private IndexLoaderInterface $decorated,
        private TagAwareCacheInterface $flexCache,
    ) {
    }

    public function load(string $host): array
    {
        /** @var callable(ItemInterface): FlexIndex $callback */
        $callback = fn (ItemInterface $item) => $item->set($this->decorated->load($host))->tag(self::INDEX_CACHE_KEY)->get();

        return $this->flexCache->get($this->createCacheKey($host), $callback);
    }

    private function createCacheKey(string $host): string
    {
        return str_replace(['{', '}', '(', ')', '/', '\\', '@', ':'], '_', $host);
    }
}
