<?php

declare(strict_types=1);

namespace App\Dto\Recipe;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

final readonly class Recipe
{
    /**
     * @param array<File> $files
     */
    public function __construct(
        #[NotBlank]
        public string $vendor,

        #[NotBlank]
        public string $packageName,

        #[NotBlank]
        public string $version,

        #[Valid]
        public Manifest $manifest,

        #[Valid]
        public array $files,
    ) {
    }
}
