<?php

declare(strict_types=1);

namespace App\Flex\Recipe\Dump;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class RecipeFilesystemManipulator implements RecipeDumperInterface, RecipeRemoverInterface
{
    public function __construct(
        #[Autowire(param: 'app.flex_recipes_dir')]
        private string $path,

        private Filesystem $fileSystem,
        private SerializerInterface $serializer,
    ) {
    }

    public function dump(string $filename, DumpRecipeContext $context): void
    {
        $this->fileSystem->dumpFile(
            $this->path.DIRECTORY_SEPARATOR.$filename,
            $this->serializer->serialize($context->toArray(), JsonEncoder::FORMAT)
        );
    }

    public function remove(string $filename): void
    {
        $this->fileSystem->remove([$this->path.DIRECTORY_SEPARATOR.$filename]);
    }
}
