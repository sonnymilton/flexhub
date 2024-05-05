<?php

declare(strict_types=1);

namespace App\Entity;

use App\Flex\Recipe\Dump\DumpRecipeContext;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Manifest
{
    #[ORM\Column]
    private string $bundle;

    #[ORM\Column(type: 'string')]
    private string $bundleEvn;

    /** @var array<string, string> */
    #[ORM\Column(type: 'json')]
    private array $copyFromRecipe;

    /** @var array<string, string> */
    #[ORM\Column(type: 'json')]
    private array $copyFromPackage;

    /** @var array<string, string> */
    #[ORM\Column(type: 'json')]
    private array $env;

    /** @var array<string> */
    #[ORM\Column(type: 'json')]
    private array $gitignore;

    /** @var array<string, string> */
    #[ORM\Column(type: 'json')]
    private array $composerScripts;

    /**
     * @param array<string, string> $copyFromRecipe
     * @param array<string, string> $copyFromPackage
     * @param array<string>         $env
     * @param array<string, string> $gitignore
     * @param array<string, string> $composerScripts
     */
    public function __construct(string $bundle, string $bundleEvn, array $copyFromRecipe, array $copyFromPackage, array $env, array $gitignore, array $composerScripts)
    {
        $this->bundle = $bundle;
        $this->bundleEvn = $bundleEvn;
        $this->copyFromRecipe = $copyFromRecipe;
        $this->copyFromPackage = $copyFromPackage;
        $this->env = $env;
        $this->gitignore = $gitignore;
        $this->composerScripts = $composerScripts;
    }

    public function dumpInto(DumpRecipeContext $context): void
    {
        $context->addManifest(array_filter([
            'bundles' => [
                $this->bundle => [$this->bundleEvn],
            ],
            'copy-from-recipe' => $this->copyFromRecipe,
            'copy-from-package' => $this->copyFromPackage,
            'env' => $this->env,
            'gitignore' => $this->gitignore,
            'composer-scripts' => $this->composerScripts,
        ]));
    }
}
