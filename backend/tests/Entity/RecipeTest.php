<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\File;
use App\Entity\Manifest;
use App\Entity\Recipe;
use App\Flex\Recipe\Dump\DumpRecipeContext;
use App\Flex\Recipe\Dump\RecipeDumperInterface;
use PHPUnit\Framework\TestCase;

final class RecipeTest extends TestCase
{
    public function testDump(): void
    {
        $recipe = new Recipe(
            vendor: 'acme',
            packageName: 'demo-bundle',
            version: '1.0.0',
            manifest: new Manifest(
                bundle: 'Acme\\DemoBundle',
                bundleEvn: 'all',
                copyFromRecipe: ['config' => '%CONFIG_DIR%'],
                copyFromPackage: [],
                env: ['BLOG_URL' => 'http://localhost:8000'],
                gitignore: ['bundle.cache'],
                composerScripts: []
            ),
            files: [new File('config/packages/acme_demo.yaml', '#acme_demo:', false)]
        );
        $recipeDumperMock = new class() implements RecipeDumperInterface {
            public string $filename;
            public DumpRecipeContext $context;

            public function dump(string $filename, DumpRecipeContext $context): void
            {
                $this->filename = $filename;
                $this->context = $context;
            }
        };

        $recipe->dump($recipeDumperMock);
        $dumpedFileName = $recipeDumperMock->filename;
        $dumpedContext = $recipeDumperMock->context;

        $this->assertSame('acme.demo-bundle.1.0.0.json', $dumpedFileName);
        $this->assertCount(1, $dumpedContext->toArray());
    }
}
