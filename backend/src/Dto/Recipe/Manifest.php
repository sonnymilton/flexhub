<?php

declare(strict_types=1);

namespace App\Dto\Recipe;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Manifest
{
    /**
     * @param array<string, string> $copyFromRecipe
     * @param array<string, string> $copyFromPackage
     * @param array<string, string>         $env
     * @param array<string>         $gitignore
     * @param array<string, string> $composerScripts
     */
    public function __construct(
        #[Assert\NotBlank]
        public string $bundleClass,

        #[Assert\NotBlank]
        public string $bundleEnv,

        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public array $copyFromRecipe,

        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public array $copyFromPackage,

        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public array $env,

        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public array $gitignore,

        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public array $composerScripts,
    ) {
    }
}
