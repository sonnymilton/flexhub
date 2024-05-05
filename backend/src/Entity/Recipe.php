<?php

declare(strict_types=1);

namespace App\Entity;

use App\Flex\Recipe\Dump\DumpRecipeContext;
use App\Flex\Recipe\Dump\RecipeDumperInterface;
use App\Flex\Recipe\Dump\RecipeRemoverInterface;
use App\Messenger\Recipe\Dump\DumpRecipeMessage;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RecipeRepository::class, readOnly: true)]
#[ORM\UniqueConstraint(fields: ['vendor', 'packageName', 'version'])]
class Recipe
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column]
    private string $vendor;

    #[ORM\Column]
    private string $packageName;

    #[ORM\Column]
    private string $version;

    #[ORM\Embedded]
    private Manifest $manifest;

    /** @var Collection<int, File> */
    #[ORM\ManyToMany(targetEntity: File::class, cascade: ['persist'])]
    private Collection $files;

    #[ORM\Column(type: 'string', length: 32)]
    private string $ref;

    /**
     * @param array<File> $files
     */
    public function __construct(string $vendor, string $packageName, string $version, Manifest $manifest, array $files)
    {
        $this->id = Uuid::v4();

        $this->vendor = $vendor;
        $this->version = $version;
        $this->packageName = $packageName;

        $this->manifest = $manifest;
        $this->files = new ArrayCollection($files);

        $this->refreshRef();
    }

    public function queueDump(MessageBusInterface $messageBus): void
    {
        $messageBus->dispatch(
            (new Envelope(new DumpRecipeMessage($this->id)))
                ->with(new DispatchAfterCurrentBusStamp())
        );
    }

    public function dump(RecipeDumperInterface $recipeDumper): void
    {
        $context = new DumpRecipeContext("$this->vendor/$this->packageName", $this->ref);

        $this->manifest->dumpInto($context);

        foreach ($this->files as $file) {
            $file->dumpInto($context);
        }

        $recipeDumper->dump($this->filename(), $context);
    }

    public function removeAssociatedFile(RecipeRemoverInterface $recipeRemover): void
    {
        $recipeRemover->remove($this->filename());
    }

    private function filename(): string
    {
        return "$this->vendor.$this->packageName.$this->version.json";
    }

    private function refreshRef(): void
    {
        $this->ref = bin2hex(random_bytes(16));
    }
}
