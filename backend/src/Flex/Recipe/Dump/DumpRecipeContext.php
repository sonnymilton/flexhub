<?php

declare(strict_types=1);

namespace App\Flex\Recipe\Dump;

use App\Flex\Recipe\Dump\Exception\IncompleteDumpContextException;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

/**
 * @phpstan-type ManifestDumpContext array{
 *      bundles: non-empty-array<string, non-empty-array<string>>,
 *      copy-from-recipe?: array<string, string>,
 *      copy-from-package?: array<string, string>,
 *      env?: array<string, string>,
 *      gitignore?: array<string>,
 *      composer-scripts?: array<string>
 *    }
 * @phpstan-type FileDumpContext array{contents: array<string>, executable: bool}
 * @phpstan-type RecipeDumpContext array{
 *      manifests: array<string, array{
 *          manifest: ManifestDumpContext,
 *          files: array<string, FileDumpContext>,
 *          ref: string,
 *      }>
 *  }
 */
#[Exclude]
final class DumpRecipeContext
{
    /** @var array<string, FileDumpContext> */
    private array $files = [];

    /** @var ManifestDumpContext|null */
    private ?array $manifest = null;

    public function __construct(
        private readonly string $fullPackageName,
        private readonly string $ref
    ) {
    }

    /**
     * @param FileDumpContext $fileDump
     */
    public function addFile(string $path, array $fileDump): void
    {
        $this->files[$path] = $fileDump;
    }

    /**
     * @param ManifestDumpContext $manifest
     */
    public function addManifest(array $manifest): void
    {
        $this->manifest = $manifest;
    }

    /**
     * @return RecipeDumpContext
     */
    public function toArray(): array
    {
        if (null === $this->manifest) {
            throw new IncompleteDumpContextException('Cannot convert to array context without manifest');
        }

        return [
            'manifests' => [
                $this->fullPackageName => [
                    'manifest' => $this->manifest,
                    'files' => $this->files,
                    'ref' => $this->ref,
                ],
            ],
        ];
    }
}
